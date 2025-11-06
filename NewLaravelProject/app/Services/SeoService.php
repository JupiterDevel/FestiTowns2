<?php

namespace App\Services;

use Illuminate\Support\Str;

class SeoService
{
    /**
     * Genera meta tags optimizados para SEO
     */
    public static function generateMetaTags(array $data): array
    {
        $defaults = [
            'title' => config('app.name', 'FestiTowns') . ' - Festividades y Eventos en España',
            'description' => 'Descubre las mejores festividades y eventos tradicionales de España. Fallas de Valencia, San Fermín, Feria de Abril y muchas más.',
            'keywords' => 'festividades españa, eventos tradicionales, fiestas populares, turismo cultural',
            'image' => asset('favicon.ico'),
            'url' => url()->current(),
            'type' => 'website',
            'locale' => 'es_ES',
        ];

        $meta = array_merge($defaults, $data);

        // Asegurar que la URL de la imagen sea absoluta
        if (!empty($meta['image']) && !filter_var($meta['image'], FILTER_VALIDATE_URL)) {
            $meta['image'] = url($meta['image']);
        }

        // Limpiar y optimizar description
        $meta['description'] = self::cleanDescription($meta['description']);

        return $meta;
    }

    /**
     * Genera título SEO optimizado para festividades
     */
    public static function generateFestivityTitle($festivity, $year = null): string
    {
        $year = $year ?? $festivity->start_date->year ?? date('Y');
        $locality = $festivity->locality->name ?? '';
        
        return "{$festivity->name} {$year} en {$locality}: Horarios, Eventos y Tradiciones | FestiTowns";
    }

    /**
     * Genera descripción SEO optimizada para festividades
     */
    public static function generateFestivityDescription($festivity): string
    {
        $locality = $festivity->locality->name ?? '';
        $province = $festivity->province ?? '';
        $startDate = $festivity->start_date->format('d/m/Y') ?? '';
        $endDate = $festivity->end_date ? $festivity->end_date->format('d/m/Y') : $startDate;
        
        $description = "Descubre todo sobre {$festivity->name} en {$locality}, {$province}. ";
        $description .= "Fechas: del {$startDate} al {$endDate}. ";
        $description .= "Información sobre eventos, horarios, tradiciones y cómo disfrutar de esta festividad única. ";
        $description .= "Vota y comparte tu experiencia.";
        
        return self::cleanDescription($description);
    }

    /**
     * Genera título SEO optimizado para localidades
     */
    public static function generateLocalityTitle($locality): string
    {
        $province = $locality->province ?? '';
        return "{$locality->name}, {$province} - Festividades y Lugares de Interés | FestiTowns";
    }

    /**
     * Genera descripción SEO optimizada para localidades
     */
    public static function generateLocalityDescription($locality): string
    {
        $festivitiesCount = $locality->festivities->count() ?? 0;
        $description = "Explora {$locality->name}, {$locality->province}. ";
        $description .= "Descubre sus {$festivitiesCount} festividades tradicionales, lugares de interés y monumentos. ";
        $description .= "Planifica tu visita y conoce la cultura y tradiciones de esta localidad española.";
        
        return self::cleanDescription($description);
    }

    /**
     * Genera keywords SEO optimizadas
     */
    public static function generateKeywords($type, $data): string
    {
        $keywords = [];
        
        switch ($type) {
            case 'festivity':
                $keywords[] = strtolower($data['name'] ?? '');
                $keywords[] = strtolower($data['locality'] ?? '');
                $keywords[] = strtolower($data['province'] ?? '');
                $keywords[] = 'festividad';
                $keywords[] = 'fiesta tradicional';
                $keywords[] = 'evento cultural';
                $keywords[] = 'turismo';
                $keywords[] = 'españa';
                break;
                
            case 'locality':
                $keywords[] = strtolower($data['name'] ?? '');
                $keywords[] = strtolower($data['province'] ?? '');
                $keywords[] = 'localidad';
                $keywords[] = 'turismo';
                $keywords[] = 'festividades';
                $keywords[] = 'lugares de interés';
                $keywords[] = 'españa';
                break;
                
            default:
                $keywords = ['festividades españa', 'eventos tradicionales', 'fiestas populares', 'turismo cultural'];
        }
        
        return implode(', ', array_filter($keywords));
    }

    /**
     * Limpia y optimiza descripciones para SEO
     */
    private static function cleanDescription(string $description, int $maxLength = 160): string
    {
        // Eliminar HTML
        $description = strip_tags($description);
        
        // Normalizar espacios
        $description = preg_replace('/\s+/', ' ', $description);
        $description = trim($description);
        
        // Truncar si es necesario
        if (mb_strlen($description) > $maxLength) {
            $description = mb_substr($description, 0, $maxLength - 3) . '...';
        }
        
        return $description;
    }

    /**
     * Genera Schema.org JSON-LD para Event (Festividad)
     */
    public static function generateEventSchema($festivity): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $festivity->name,
            'description' => self::cleanDescription($festivity->description, 500),
            'startDate' => $festivity->start_date->toIso8601String(),
            'location' => [
                '@type' => 'Place',
                'name' => $festivity->locality->name ?? '',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $festivity->locality->name ?? '',
                    'addressRegion' => $festivity->province ?? '',
                    'addressCountry' => 'ES',
                ],
            ],
        ];

        if ($festivity->end_date) {
            $schema['endDate'] = $festivity->end_date->toIso8601String();
        }

        if ($festivity->photos && count($festivity->photos) > 0) {
            $schema['image'] = array_map(function ($photo) {
                return filter_var($photo, FILTER_VALIDATE_URL) ? $photo : url($photo);
            }, $festivity->photos);
        }

        if ($festivity->events && $festivity->events->count() > 0) {
            $schema['subEvent'] = $festivity->events->map(function ($event) {
                $subEvent = [
                    '@type' => 'Event',
                    'name' => $event->name,
                ];
                
                if ($event->start_time) {
                    $subEvent['startDate'] = $event->start_time->toIso8601String();
                }
                
                if ($event->end_time) {
                    $subEvent['endDate'] = $event->end_time->toIso8601String();
                }
                
                if ($event->location) {
                    $subEvent['location'] = [
                        '@type' => 'Place',
                        'name' => $event->location,
                    ];
                }
                
                if ($event->description) {
                    $subEvent['description'] = self::cleanDescription($event->description, 200);
                }
                
                return $subEvent;
            })->toArray();
        }

        return $schema;
    }

    /**
     * Genera Schema.org JSON-LD para City (Localidad)
     */
    public static function generateCitySchema($locality): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'City',
            'name' => $locality->name,
            'description' => self::cleanDescription($locality->description, 500),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $locality->name,
                'addressRegion' => $locality->province ?? '',
                'addressCountry' => 'ES',
            ],
        ];

        if ($locality->photos && count($locality->photos) > 0) {
            $schema['image'] = array_map(function ($photo) {
                return filter_var($photo, FILTER_VALIDATE_URL) ? $photo : url($photo);
            }, $locality->photos);
        }

        return $schema;
    }
}

