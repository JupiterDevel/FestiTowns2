<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Genera el sitemap.xml dinámicamente
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $sitemap .= ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
        $sitemap .= ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9';
        $sitemap .= ' http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        // Página principal
        $sitemap .= $this->addUrl(route('home'), '1.0', 'daily');

        // Páginas principales
        $sitemap .= $this->addUrl(route('localities.index'), '0.9', 'weekly');
        $sitemap .= $this->addUrl(route('festivities.index'), '0.9', 'weekly');
        $sitemap .= $this->addUrl(route('festivities.most-voted'), '0.8', 'weekly');
        $sitemap .= $this->addUrl(route('legal.index'), '0.5', 'monthly');

        // Localidades
        $localities = Locality::all();
        foreach ($localities as $locality) {
            $sitemap .= $this->addUrl(
                route('localities.show', $locality),
                '0.8',
                'monthly',
                $locality->updated_at
            );
        }

        // Festividades
        $festivities = Festivity::with('locality')->get();
        foreach ($festivities as $festivity) {
            $sitemap .= $this->addUrl(
                route('festivities.show', $festivity),
                '0.9',
                'weekly',
                $festivity->updated_at
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Genera el robots.txt dinámicamente
     */
    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n\n";
        
        $robots .= "# Sitemap\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n\n";
        
        $robots .= "# Disallow admin and private areas\n";
        $robots .= "Disallow: /profile\n";
        $robots .= "Disallow: /users\n";
        $robots .= "Disallow: /comentarios/pendientes\n";
        $robots .= "Disallow: /localidades/crear\n";
        $robots .= "Disallow: /localidades/*/editar\n";
        $robots .= "Disallow: /festividades/crear\n";
        $robots .= "Disallow: /festividades/*/editar\n";
        $robots .= "Disallow: /festividades/*/eventos/crear\n";
        $robots .= "Disallow: /festividades/*/eventos/*/editar\n\n";
        
        $robots .= "# Allow public content\n";
        $robots .= "Allow: /\n";
        $robots .= "Allow: /localidades\n";
        $robots .= "Allow: /festividades\n";
        $robots .= "Allow: /mas-votadas\n";
        $robots .= "Allow: /festividades/*\n";
        $robots .= "Allow: /localidades/*\n\n";
        
        $robots .= "# Crawl-delay\n";
        $robots .= "Crawl-delay: 1\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Añade una URL al sitemap
     */
    private function addUrl($url, $priority = '0.5', $changefreq = 'monthly', $lastmod = null)
    {
        $lastmod = $lastmod ? $lastmod->toAtomString() : now()->toAtomString();
        
        return "  <url>\n" .
               "    <loc>" . htmlspecialchars($url, ENT_XML1, 'UTF-8') . "</loc>\n" .
               "    <lastmod>" . $lastmod . "</lastmod>\n" .
               "    <changefreq>" . $changefreq . "</changefreq>\n" .
               "    <priority>" . $priority . "</priority>\n" .
               "  </url>\n";
    }
}
