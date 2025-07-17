<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get recent articles
        $recent_articles = Article::with(['category', 'admin'])
            ->where('admin_id', Auth::guard('admin')->id())
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Get popular categories
        $popular_categories = Category::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_articles', 'popular_categories'));
    }

    /**
     * Get dashboard statistics
     */
    private function getStatistics()
    {
        $adminId = Auth::guard('admin')->id();

        return [
            'total_articles' => Article::where('admin_id', $adminId)->count(),
            'published_articles' => Article::where('admin_id', $adminId)
                ->where('status', 'published')->count(),
            'draft_articles' => Article::where('admin_id', $adminId)
                ->where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count(),
            'total_visitors' => Visiteur::count(),
            'visitors_today' => Visiteur::whereDate('visit_date', today())->count(),
            'visitors_this_week' => Visiteur::whereBetween('visit_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'visitors_this_month' => Visiteur::whereMonth('visit_date', now()->month)
                ->whereYear('visit_date', now()->year)->count(),
        ];
    }

    /**
     * Get analytics data for charts (AJAX)
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '7days');
        
        switch ($period) {
            case '24hours':
                $data = $this->getHourlyVisitors();
                break;
            case '7days':
                $data = $this->getDailyVisitors(7);
                break;
            case '30days':
                $data = $this->getDailyVisitors(30);
                break;
            case '12months':
                $data = $this->getMonthlyVisitors();
                break;
            default:
                $data = $this->getDailyVisitors(7);
        }

        return response()->json($data);
    }

    /**
     * Get hourly visitors for the last 24 hours
     */
    private function getHourlyVisitors()
    {
        $visitors = Visiteur::select(
                DB::raw('HOUR(visit_date) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->where('visit_date', '>=', now()->subDay())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $data = [];
        for ($i = 0; $i < 24; $i++) {
            $visitor = $visitors->firstWhere('hour', $i);
            $data[] = [
                'label' => sprintf('%02d:00', $i),
                'value' => $visitor ? $visitor->count : 0
            ];
        }

        return $data;
    }

    /**
     * Get daily visitors for the specified number of days
     */
    private function getDailyVisitors($days)
    {
        $visitors = Visiteur::select(
                DB::raw('DATE(visit_date) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('visit_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $visitor = $visitors->firstWhere('date', $date);
            $data[] = [
                'label' => now()->subDays($i)->format('d/m'),
                'value' => $visitor ? $visitor->count : 0
            ];
        }

        return $data;
    }

    /**
     * Get monthly visitors for the last 12 months
     */
    private function getMonthlyVisitors()
    {
        $visitors = Visiteur::select(
                DB::raw('YEAR(visit_date) as year'),
                DB::raw('MONTH(visit_date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('visit_date', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $visitor = $visitors->where('year', $date->year)
                              ->where('month', $date->month)
                              ->first();
            $data[] = [
                'label' => $date->format('M Y'),
                'value' => $visitor ? $visitor->count : 0
            ];
        }

        return $data;
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        $period = $request->get('period', '30days');
        $format = $request->get('format', 'csv');

        // Get data based on period
        switch ($period) {
            case '7days':
                $data = $this->getDailyVisitors(7);
                $filename = 'analytics_7_days';
                break;
            case '30days':
                $data = $this->getDailyVisitors(30);
                $filename = 'analytics_30_days';
                break;
            case '12months':
                $data = $this->getMonthlyVisitors();
                $filename = 'analytics_12_months';
                break;
            default:
                $data = $this->getDailyVisitors(30);
                $filename = 'analytics_30_days';
        }

        if ($format === 'csv') {
            return $this->exportToCsv($data, $filename);
        }

        return response()->json(['error' => 'Format non supporté'], 400);
    }

    /**
     * Export data to CSV
     */
    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, ['Date', 'Visiteurs'], ';');
            
            // Add data
            foreach ($data as $row) {
                fputcsv($file, [$row['label'], $row['value']], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
