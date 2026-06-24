<?php

use Illuminate\Support\Facades\Lang;

if (!function_exists('generateDashboardBreadcrumb')) {
    function generateDashboardBreadcrumb($pageTitle = null)
    {
        $segments = request()->segments();
        $html = '';
        $url = '';

        // Buang numeric (ID)
        $filteredSegments = array_values(array_filter($segments, function ($segment) {
            return !is_numeric($segment);
        }));

        $total = count($filteredSegments);

        foreach ($filteredSegments as $index => $segment) {

            $isLast = $index === $total - 1;

            // Skip segment terakhir (akan diganti title)
            if ($isLast) {
                continue;
            }

            $url .= '/' . $segment;

            $label = Lang::has("breadcrumbs.$segment")
                ? __("breadcrumbs.$segment")
                : ucfirst(str_replace('-', ' ', $segment));

            $html .= '<li class="breadcrumb-item">
                        <a href="' . url($url) . '">' . $label . '</a>
                      </li>';
        }

        // Tambahkan Title sebagai item aktif terakhir
        if ($pageTitle) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">'
                . e($pageTitle) .
                '</li>';
        }

        return $html;
    }
}

if (!function_exists('generateLandingBreadcrumb')) {
    function generateLandingBreadcrumb($pageTitle = null)
    {
        $segments = request()->segments();
        $html = '';
        $url = '';

        // Tambahkan Home sebagai item pertama
        $html .= '<li class="breadcrumb-item">
                    <a href="' . route('home') . '">' . __('breadcrumbs.home') . '</a>
                  </li>';

        // Buang numeric (ID)
        $filteredSegments = array_values(array_filter($segments, function ($segment) {
            return !is_numeric($segment);
        }));

        $total = count($filteredSegments);

        foreach ($filteredSegments as $index => $segment) {
            $isLast = $index === $total - 1;

            // Skip segment terakhir (akan diganti title)
            if ($isLast) {
                continue;
            }

            $url .= '/' . $segment;

            $label = Lang::has("breadcrumbs.$segment")
                ? __("breadcrumbs.$segment")
                : ucfirst(str_replace('-', ' ', $segment));

            $html .= '<li class="breadcrumb-item">
                        <a href="' . url($url) . '">' . $label . '</a>
                      </li>';
        }

        // Tambahkan Title sebagai item aktif terakhir
        if ($pageTitle) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">'
                . e($pageTitle) .
                '</li>';
        } elseif ($total > 0) {
            // Kalau pageTitle tidak diberikan, ambil segment terakhir sebagai aktif
            $lastSegment = $filteredSegments[$total - 1];
            $label = Lang::has("breadcrumbs.$lastSegment")
                ? __("breadcrumbs.$lastSegment")
                : ucfirst(str_replace('-', ' ', $lastSegment));

            $html .= '<li class="breadcrumb-item active" aria-current="page">'
                . e($label) .
                '</li>';
        }

        return $html;
    }
}
