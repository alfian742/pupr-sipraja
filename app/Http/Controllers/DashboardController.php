<?php

namespace App\Http\Controllers;

use App\Models\MainPerformanceIndicator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Dashboard utama
     */
    public function index()
    {
        $visitorData = $this->visitorTracker();
        $userActivities = User::select('name', 'last_login_at')
            ->orderByDesc('last_login_at')
            ->limit(10)
            ->get();

        $mainIndicatorNames = MainPerformanceIndicator::query()
            ->whereNotNull('indicator_name')
            ->orderBy('indicator_name', 'asc')
            ->pluck('indicator_name')
            ->unique()
            ->values();

        return view('dashboard.index', compact('visitorData', 'userActivities', 'mainIndicatorNames'));
    }

    /**
     * Halaman About
     */
    public function about()
    {
        return view('dashboard.about');
    }

    /**
     * Halaman About
     */
    public function development()
    {
        return view('dashboard.development');
    }

    /**
     * Ambil & format data visitor
     */
    private function visitorTracker()
    {
        $disk = Storage::disk('templates');
        $file = 'visitor-counters/visitors.json';

        if (!$disk->exists($file)) {
            return null;
        }

        $data = json_decode($disk->get($file), true);

        return (object) [
            'total'   => number_format($data['total'] ?? 0, 0, ',', '.'),
            'monthly' => number_format($data['monthly']['count'] ?? 0, 0, ',', '.'),
            'today'   => number_format($data['today']['count'] ?? 0, 0, ',', '.'),
            'online'  => number_format(count($data['online'] ?? []), 0, ',', '.'),
            'daily'   => $data['daily'] ?? []
        ];
    }
}
