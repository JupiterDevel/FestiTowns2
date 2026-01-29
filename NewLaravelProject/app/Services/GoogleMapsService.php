<?php

namespace App\Services;

class GoogleMapsService
{
    /**
     * Extract latitude and longitude from a Google Maps URL.
     * 
     * Supports multiple Google Maps URL formats:
     * - https://maps.google.com/?q=lat,lng
     * - https://www.google.com/maps/place/.../@lat,lng
     * - https://www.google.com/maps/@lat,lng,zoom
     * - https://maps.google.com/maps?q=lat,lng
     * 
     * @param string $url
     * @return array{latitude: float|null, longitude: float|null}
     */
    public function extractCoordinatesFromUrl(string $url): array
    {
        $latitude = null;
        $longitude = null;

        // Parse URL
        $parsedUrl = parse_url($url);
        
        if (!$parsedUrl) {
            return ['latitude' => null, 'longitude' => null];
        }

        // Method 1: Query parameter ?q=lat,lng
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            
            if (isset($queryParams['q'])) {
                $coords = $this->parseCoordinateString($queryParams['q']);
                if ($coords) {
                    return $coords;
                }
            }
        }

        // Method 2: Path with @lat,lng format
        // Example: /maps/@40.7128,-74.0060,15z
        if (isset($parsedUrl['path'])) {
            if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $parsedUrl['path'], $matches)) {
                $latitude = (float) $matches[1];
                $longitude = (float) $matches[2];
                
                if ($this->isValidCoordinate($latitude, $longitude)) {
                    return ['latitude' => $latitude, 'longitude' => $longitude];
                }
            }
        }

        // Method 3: Path with /place/.../@lat,lng format
        // Example: /maps/place/Place+Name/@40.7128,-74.0060,15z
        if (isset($parsedUrl['path']) && strpos($parsedUrl['path'], '/place/') !== false) {
            if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $parsedUrl['path'], $matches)) {
                $latitude = (float) $matches[1];
                $longitude = (float) $matches[2];
                
                if ($this->isValidCoordinate($latitude, $longitude)) {
                    return ['latitude' => $latitude, 'longitude' => $longitude];
                }
            }
        }

        // Method 4: Try to extract from full URL string
        if (preg_match('/(-?\d+\.?\d*),(-?\d+\.?\d*)/', $url, $matches)) {
            $latitude = (float) $matches[1];
            $longitude = (float) $matches[2];
            
            if ($this->isValidCoordinate($latitude, $longitude)) {
                return ['latitude' => $latitude, 'longitude' => $longitude];
            }
        }

        return ['latitude' => null, 'longitude' => null];
    }

    /**
     * Parse a coordinate string like "40.7128,-74.0060" or "40.7128, -74.0060"
     * 
     * @param string $coordinateString
     * @return array{latitude: float|null, longitude: float|null}|null
     */
    protected function parseCoordinateString(string $coordinateString): ?array
    {
        // Remove extra whitespace and split by comma
        $coords = array_map('trim', explode(',', $coordinateString));
        
        if (count($coords) >= 2) {
            $latitude = (float) $coords[0];
            $longitude = (float) $coords[1];
            
            if ($this->isValidCoordinate($latitude, $longitude)) {
                return ['latitude' => $latitude, 'longitude' => $longitude];
            }
        }

        return null;
    }

    /**
     * Validate if coordinates are within valid ranges.
     * 
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    protected function isValidCoordinate(float $latitude, float $longitude): bool
    {
        return $latitude >= -90 && $latitude <= 90 
            && $longitude >= -180 && $longitude <= 180;
    }
}

