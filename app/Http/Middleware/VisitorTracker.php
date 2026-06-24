<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class VisitorTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $disk = Storage::disk('templates'); // <-- gunakan disk templates
        $file = 'visitor-counters/visitors.json'; // relatif dari root disk templates
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');
        $currentYear = date('Y');
        $ip = $request->ip();
        $now = time();

        // ===== Buat file jika belum ada =====
        if (!$disk->exists($file)) {
            $disk->put($file, json_encode([
                'total' => 0,
                'year' => $currentYear,
                'monthly' => ['month' => '', 'count' => 0],
                'today' => ['date' => '', 'count' => 0],
                'daily' => [],
                'ips' => [],
                'online' => []
            ], JSON_PRETTY_PRINT));
        }

        $data = json_decode($disk->get($file), true);

        // ===== Reset Total Tahunan =====
        if (!isset($data['year']) || $data['year'] != $currentYear) {
            $data['total'] = 0;
            $data['year'] = $currentYear;
            $data['ips'] = [];
            $data['daily'] = [];
        }

        // ===== Reset Bulanan =====
        if (!isset($data['monthly']['month']) || $data['monthly']['month'] !== $currentMonth) {
            $data['monthly']['month'] = $currentMonth;
            $data['monthly']['count'] = 0;
        }

        // ===== Reset Harian =====
        if (!isset($data['today']['date']) || $data['today']['date'] !== $today) {
            $data['today']['date'] = $today;
            $data['today']['count'] = 0;
        }

        // ===== Hitung 1x per session =====
        if (!$request->session()->has('visited')) {
            $data['total']++;
            $data['monthly']['count']++;

            $data['daily'][$today] = ($data['daily'][$today] ?? 0) + 1;
            $data['today']['count'] = $data['daily'][$today];

            if (!in_array($ip, $data['ips'])) {
                $data['ips'][] = $ip;
            }

            $data['online'][$ip] = $now;

            $request->session()->put('visited', true);
        }

        // Hapus online user > 5 menit
        foreach ($data['online'] as $userIp => $timestamp) {
            if ($now - $timestamp > 300) {
                unset($data['online'][$userIp]);
            }
        }

        $disk->put($file, json_encode($data, JSON_PRETTY_PRINT));

        // ===== Object visitor =====
        $visitor = new stdClass();
        $visitor->total = number_format($data['total'], 0, ',', '.');
        $visitor->monthly = number_format($data['monthly']['count'], 0, ',', '.');
        $visitor->today = number_format($data['today']['count'], 0, ',', '.');
        $visitor->online = number_format(count($data['online']), 0, ',', '.');

        view()->share('visitorData', $visitor);
        $request->attributes->set('visitor', $visitor);

        return $next($request);
    }
}
