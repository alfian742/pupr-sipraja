<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\DownloadCenter;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Sitemap XML untuk halaman publik yang layak diindeks.
     */
    public function sitemap(): Response
    {
        $urls = [];

        $this->addUrl($urls, route('home'), 'daily', '1.0');
        $this->addUrl($urls, route('public-services'), 'weekly', '0.8');
        $this->addUrl($urls, route('organization-profiles.organization-structure'), 'monthly', '0.7');
        $this->addUrl($urls, route('organization-profiles.vision-and-mission'), 'monthly', '0.7');
        $this->addUrl($urls, route('organization-profiles.personnel-profiles'), 'weekly', '0.7');
        $this->addUrl($urls, route('other-informations.faqs'), 'weekly', '0.7');
        $this->addUrl($urls, route('other-informations.download-center.index'), 'weekly', '0.8');
        $this->addUrl($urls, route('blog.index'), 'daily', '0.8');
        $this->addUrl($urls, route('contact'), 'monthly', '0.6');
        $this->addUrl($urls, route('ikli-survey.home'), 'weekly', '0.7');

        BlogCategory::query()
            ->active()
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->chunkById(500, function ($categories) use (&$urls) {
                foreach ($categories as $category) {
                    $this->addUrl(
                        $urls,
                        route('blog.category', $category->slug),
                        'weekly',
                        '0.7',
                        $category->updated_at?->toDateString()
                    );
                }
            });

        BlogArticle::query()
            ->published()
            ->select(['id', 'slug', 'updated_at', 'published_at'])
            ->orderBy('id')
            ->chunkById(500, function ($articles) use (&$urls) {
                foreach ($articles as $article) {
                    $this->addUrl(
                        $urls,
                        route('blog.show', $article->slug),
                        'weekly',
                        '0.8',
                        ($article->updated_at ?? $article->published_at)?->toDateString()
                    );
                }
            });

        DownloadCenter::query()
            ->where('status', 'publish')
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->chunkById(500, function ($documents) use (&$urls) {
                foreach ($documents as $document) {
                    $this->addUrl(
                        $urls,
                        route('other-informations.download-center.show', $document->slug),
                        'monthly',
                        '0.6',
                        $document->updated_at?->toDateString()
                    );
                }
            });

        $xml = $this->buildXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function addUrl(array &$urls, string $loc, string $changefreq, string $priority, ?string $lastmod = null): void
    {
        $urls[] = compact('loc', 'changefreq', 'priority', 'lastmod');
    }

    private function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . $this->escape($url['loc']) . '</loc>' . PHP_EOL;

            if (! empty($url['lastmod'])) {
                $xml .= '        <lastmod>' . $this->escape($url['lastmod']) . '</lastmod>' . PHP_EOL;
            }

            $xml .= '        <changefreq>' . $this->escape($url['changefreq']) . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $this->escape($url['priority']) . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        return $xml;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
