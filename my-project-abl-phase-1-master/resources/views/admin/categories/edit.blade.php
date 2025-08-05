@extends('layouts.admin')

@section('title', 'Modifier la catégorie')
@section('page-title', 'Modifier la catégorie')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Modifier la catégorie</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $category->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('public.category.filter', $category->slug) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Voir sur le site
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nom de la catégorie <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $category->name) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                       placeholder="Ex: Développement Web, Tutoriels, etc.">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Slug -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    URL actuelle (slug)
                </label>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ url('/category/') }}/</span>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400 ml-1">{{ $category->slug }}</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    L'URL sera mise à jour automatiquement si vous modifiez le nom
                </p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Décrivez brièvement cette catégorie (optionnel)">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cette description peut être affichée sur le site public</p>
            </div>

            <!-- Image de catégorie -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $category->image ? 'Remplacer l\'image de la catégorie' : 'Image de la catégorie' }}
                </label>

                <!-- Image actuelle -->
                @if($category->image)
                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image actuelle :</p>
                        <img src="{{ Storage::url($category->image) }}"
                             alt="{{ $category->name }}"
                             class="max-h-32 rounded-lg shadow-md">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Cette image est actuellement utilisée par {{ $category->articles()->whereNull('image')->count() }} article(s) sans image spécifique.
                        </p>
                    </div>
                @endif

                <div id="dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors duration-300">
                    <div class="space-y-1 text-center">
                        <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div id="upload-text" class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>{{ $category->image ? 'Changer l\'image' : 'Télécharger un fichier' }}</span>
                                <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF jusqu'à 10MB</p>

                        <!-- Prévisualisation de la nouvelle image -->
                        <div id="image-preview" class="hidden mt-4">
                            <img id="preview-img" src="" alt="Prévisualisation" class="mx-auto max-h-48 rounded-lg shadow-md">
                            <button type="button" id="remove-image" class="mt-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                Annuler le changement
                            </button>
                        </div>
                    </div>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Important :</strong> Cette image sera utilisée par défaut pour tous les articles de cette catégorie qui n'ont pas d'image spécifique.
                        @if($category->articles()->whereNull('image')->count() > 0)
                            <br>Actuellement, <strong>{{ $category->articles()->whereNull('image')->count() }} article(s)</strong> utiliseront cette nouvelle image.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Statistiques</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $category->articles_count }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Article(s) associé(s)</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $category->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Date de création</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Articles in this category -->
    @if($category->articles_count > 0)
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Articles dans cette catégorie</h3>
            <div class="space-y-3">
                @foreach($category->articles()->latest()->take(5)->get() as $article)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $article->title }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $article->status === 'published' ? 'Publié' : 'Brouillon' }} • 
                                {{ $article->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.articles.edit', $article) }}" 
                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                @endforeach
                
                @if($category->articles_count > 5)
                    <div class="text-center">
                        <a href="{{ route('admin.articles.index', ['category' => $category->id]) }}" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                            Voir tous les articles ({{ $category->articles_count }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Warning for deletion -->
    @if($category->articles_count > 0)
        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Attention</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        Cette catégorie contient {{ $category->articles_count }} article(s). 
                        Si vous la supprimez, les articles ne seront pas supprimés mais n'auront plus de catégorie.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('image');
    const uploadIcon = document.getElementById('upload-icon');
    const uploadText = document.getElementById('upload-text');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const removeImageBtn = document.getElementById('remove-image');

    // Gestion du drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    dropzone.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropzone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
    }

    function unhighlight(e) {
        dropzone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            handleFiles(files);
        }
    }

    // Gestion de la sélection de fichier
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFiles(e.target.files);
        }
    });

    function handleFiles(files) {
        const file = files[0];

        // Vérification du type de fichier
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image.');
            return;
        }

        // Vérification de la taille (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('Le fichier est trop volumineux. Taille maximum : 10MB.');
            return;
        }

        // Mise à jour de l'input file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Prévisualisation
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadIcon.classList.add('hidden');
            uploadText.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Suppression de l'image
    removeImageBtn.addEventListener('click', function() {
        fileInput.value = '';
        previewImg.src = '';
        uploadIcon.classList.remove('hidden');
        uploadText.classList.remove('hidden');
        imagePreview.classList.add('hidden');
    });
});
</script>
@endpush
