<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Constructeur - Appliquer le middleware d'authentification admin
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Upload une image et retourne les informations du fichier
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // Validation du fichier uploadé
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'type' => 'sometimes|string|in:article,profile,general'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->get('type', 'general');
            
            // Générer un nom unique pour le fichier
            $filename = $this->generateUniqueFilename($file);
            
            // Définir le dossier de destination selon le type
            $folder = $this->getUploadFolder($type);
            
            // Chemin complet
            $path = $folder . '/' . $filename;
            
            // Redimensionner et optimiser l'image
            $processedImage = $this->processImage($file, $type);
            
            // Sauvegarder l'image traitée
            Storage::disk('public')->put($path, $processedImage);
            
            // Générer les différentes tailles si nécessaire
            $thumbnails = $this->generateThumbnails($file, $folder, $filename, $type);
            
            // Informations du fichier
            $fileInfo = [
                'success' => true,
                'message' => 'Image uploadée avec succès !',
                'data' => [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'dimensions' => $this->getImageDimensions($file),
                    'thumbnails' => $thumbnails,
                    'type' => $type,
                    'uploaded_at' => now()->toISOString()
                ]
            ];
            
            return response()->json($fileInfo);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple images
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|max:10',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'sometimes|string|in:article,profile,general'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $index => $file) {
            try {
                $type = $request->get('type', 'general');
                $filename = $this->generateUniqueFilename($file);
                $folder = $this->getUploadFolder($type);
                $path = $folder . '/' . $filename;
                
                $processedImage = $this->processImage($file, $type);
                Storage::disk('public')->put($path, $processedImage);
                
                $thumbnails = $this->generateThumbnails($file, $folder, $filename, $type);
                
                $uploadedFiles[] = [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'size' => $file->getSize(),
                    'thumbnails' => $thumbnails
                ];
                
            } catch (\Exception $e) {
                $errors[] = [
                    'file_index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => count($uploadedFiles) > 0,
            'message' => count($uploadedFiles) . ' fichier(s) uploadé(s) avec succès',
            'uploaded_files' => $uploadedFiles,
            'errors' => $errors
        ]);
    }

    /**
     * Supprime un fichier uploadé
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Chemin du fichier requis'
            ], 422);
        }

        try {
            $path = $request->path;
            
            if (Storage::disk('public')->exists($path)) {
                // Supprimer le fichier principal
                Storage::disk('public')->delete($path);
                
                // Supprimer les thumbnails associés
                $this->deleteThumbnails($path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier supprimé avec succès'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Fichier non trouvé'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un nom de fichier unique
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function generateUniqueFilename($file)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Détermine le dossier d'upload selon le type
     * 
     * @param string $type
     * @return string
     */
    private function getUploadFolder($type)
    {
        switch ($type) {
            case 'article':
                return 'uploads/articles';
            case 'profile':
                return 'uploads/profiles';
            default:
                return 'uploads/general';
        }
    }

    /**
     * Traite et optimise l'image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type
     * @return string
     */ private function processImage($file, $type)
    {
        $image = Image::make($file);

        switch ($type) {
            case 'article':
                // Redimensionner pour les articles (max 1200px de largeur)
                if ($image->width() > 1200) {
                    $image->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                break;
                
            case 'profile':
                // Redimensionner pour les profils (carré 300x300)
                $image->fit(300, 300);
                break;
        }
        
        // Optimiser la qualité
        return $image->encode('jpg', 85)->__toString();
    }

    /**
     * Génère les thumbnails de différentes tailles
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param string $filename
     * @param string $type
     * @return array
     */
    private function generateThumbnails($file, $folder, $filename, $type)
    {
        $thumbnails = [];
        
        if ($type === 'article') {
            $sizes = [
                'small' => [300, 200],
                'medium' => [600, 400],
                'large' => [900, 600]
            ];
            
            foreach ($sizes as $sizeName => $dimensions) {
                try {
                    $image = Image::make($file);
                    $image->fit($dimensions[0], $dimensions[1]);
                    
                    $thumbFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . $sizeName . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                    $thumbPath = $folder . '/thumbs/' . $thumbFilename;
                    
                    Storage::disk('public')->put($thumbPath, $image->encode('jpg', 80)->__toString());
                    
                    $thumbnails[$sizeName] = [
                        'path' => $thumbPath,
                        'url' => Storage::disk('public')->url($thumbPath),
                        'width' => $dimensions[0],
                        'height' => $dimensions[1]
                    ];
                } catch (\Exception $e) {
                    // Ignorer les erreurs de thumbnail
                }
            }
        }
        
        return $thumbnails;
    }

    /**
     * Supprime les thumbnails associés à un fichier
     * 
     * @param string $originalPath
     * @return void
     */
    private function deleteThumbnails($originalPath)
    {
        $pathInfo = pathinfo($originalPath);
        $thumbsFolder = $pathInfo['dirname'] . '/thumbs/';
        $baseFilename = $pathInfo['filename'];
        
        $thumbnailSizes = ['small', 'medium', 'large'];
        
        foreach ($thumbnailSizes as $size) {
            $thumbPath = $thumbsFolder . $baseFilename . '_' . $size . '.' . $pathInfo['extension'];
            if (Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }
    }

    /**
     * Récupère les dimensions d'une image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    private function getImageDimensions($file)
    {
        try {
            $image = Image::make($file);
            return [
                'width' => $image->width(),
                'height' => $image->height()
            ];
        } catch (\Exception $e) {
            return ['width' => null, 'height' => null];
        }
    }
}
