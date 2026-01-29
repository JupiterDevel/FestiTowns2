<?php

namespace App\Services;

/**
 * Servicio de búsqueda inteligente con soporte para:
 * - Normalización de acentos y caracteres especiales
 * - Expansión de consultas con sinónimos en español
 * - Cálculo de relevancia para ordenamiento de resultados
 */
class SearchService
{
    /**
     * Normaliza el texto para búsquedas insensibles a acentos y caracteres especiales.
     * 
     * Convierte el texto a minúsculas y reemplaza todos los acentos y caracteres especiales
     * por sus equivalentes sin acento. Esto permite que búsquedas como "Valencia" 
     * encuentren resultados con "València".
     * 
     * @param string $text Texto a normalizar
     * @return string Texto normalizado sin acentos
     */
    public function normalizeText($text)
    {
        if (empty($text)) {
            return '';
        }
        
        // Convertir a minúsculas
        $text = mb_strtolower($text, 'UTF-8');
        
        // Reemplazar acentos y caracteres especiales
        $replacements = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c',
            'Á' => 'a', 'À' => 'a', 'Ä' => 'a', 'Â' => 'a', 'Ã' => 'a',
            'É' => 'e', 'È' => 'e', 'Ë' => 'e', 'Ê' => 'e',
            'Í' => 'i', 'Ì' => 'i', 'Ï' => 'i', 'Î' => 'i',
            'Ó' => 'o', 'Ò' => 'o', 'Ö' => 'o', 'Ô' => 'o', 'Õ' => 'o',
            'Ú' => 'u', 'Ù' => 'u', 'Ü' => 'u', 'Û' => 'u',
            'Ñ' => 'n', 'Ç' => 'c'
        ];
        
        return strtr($text, $replacements);
    }

    /**
     * Expande la consulta de búsqueda con sinónimos y variaciones comunes en español.
     * 
     * Toma una consulta de búsqueda y la expande con sinónimos relacionados.
     * Por ejemplo, si el usuario busca "fiesta", también buscará "festividad", 
     * "evento", "festival", etc. Esto mejora la experiencia de búsqueda al encontrar
     * resultados relacionados incluso si el usuario usa términos diferentes.
     * 
     * @param string $query Consulta de búsqueda original
     * @return array Array de consultas expandidas (incluye la original y sinónimos)
     */
    public function expandSearchQuery($query)
    {
        if (empty($query)) {
            return [];
        }
        
        $normalizedQuery = $this->normalizeText($query);
        
        // Sinónimos y variaciones comunes para festividades y localidades
        $synonyms = [
            'fiesta' => ['festividad', 'celebracion', 'evento', 'festival'],
            'festividad' => ['fiesta', 'celebracion', 'evento', 'festival'],
            'celebracion' => ['fiesta', 'festividad', 'evento', 'festival'],
            'evento' => ['fiesta', 'festividad', 'celebracion', 'festival'],
            'festival' => ['fiesta', 'festividad', 'celebracion', 'evento'],
            'feria' => ['mercado', 'exposicion', 'muestra'],
            'carnaval' => ['carnavales', 'mascarada'],
            'navidad' => ['navideño', 'navideña'],
            'semana santa' => ['santa semana', 'pascua'],
            'verano' => ['estival', 'estivales'],
            'invierno' => ['invernal', 'invernales'],
            'pueblo' => ['localidad', 'municipio', 'ciudad'],
            'localidad' => ['pueblo', 'municipio', 'ciudad'],
            'municipio' => ['pueblo', 'localidad', 'ciudad'],
            'ciudad' => ['pueblo', 'localidad', 'municipio'],
        ];
        
        $expandedQueries = [$query, $normalizedQuery];
        
        // Agregar sinónimos si se encuentra una coincidencia
        foreach ($synonyms as $key => $values) {
            if (strpos($normalizedQuery, $key) !== false) {
                $expandedQueries = array_merge($expandedQueries, $values);
            }
        }
        
        return array_unique($expandedQueries);
    }

    /**
     * Calcula el score de relevancia para ordenamiento de resultados.
     * 
     * Asigna un score numérico basado en qué tan bien coincide el texto con la consulta:
     * - 1: Coincidencia exacta (con o sin acentos)
     * - 2: El texto empieza con la consulta
     * - 3: El texto contiene la consulta
     * - 4: No hay coincidencia
     * 
     * Los resultados se ordenan de menor a mayor score (más relevante primero).
     * 
     * @param string $text Texto a evaluar
     * @param string $query Consulta de búsqueda
     * @return int Score de relevancia (1-4)
     */
    public function calculateRelevanceScore($text, $query)
    {
        if (empty($text) || empty($query)) {
            return 4;
        }
        
        $textLower = mb_strtolower($text, 'UTF-8');
        $queryLower = mb_strtolower($query, 'UTF-8');
        $normalizedText = $this->normalizeText($text);
        $normalizedQuery = $this->normalizeText($query);
        
        // Búsqueda exacta (con o sin acentos)
        if ($textLower === $queryLower || $normalizedText === $normalizedQuery) {
            return 1;
        }
        
        // Empieza con (con o sin acentos)
        if (strpos($textLower, $queryLower) === 0 || strpos($normalizedText, $normalizedQuery) === 0) {
            return 2;
        }
        
        // Contiene (con o sin acentos)
        if (strpos($textLower, $queryLower) !== false || strpos($normalizedText, $normalizedQuery) !== false) {
            return 3;
        }
        
        return 4;
    }

    /**
     * Verifica si un texto coincide con alguna de las consultas expandidas.
     * 
     * Realiza una búsqueda inteligente que considera:
     * - Búsqueda exacta (case-insensitive)
     * - Búsqueda normalizada (sin acentos)
     * - Búsqueda por palabras individuales (para consultas de múltiples palabras)
     * 
     * @param string $text Texto en el que buscar
     * @param array $expandedQueries Array de consultas expandidas (incluye sinónimos)
     * @return bool true si hay coincidencia, false en caso contrario
     */
    public function matchesExpandedQuery($text, $expandedQueries)
    {
        if (empty($text) || empty($expandedQueries)) {
            return false;
        }
        
        $normalizedText = $this->normalizeText($text);
        $textLower = mb_strtolower($text, 'UTF-8');
        
        foreach ($expandedQueries as $searchTerm) {
            $normalizedTerm = $this->normalizeText($searchTerm);
            $searchTermLower = mb_strtolower($searchTerm, 'UTF-8');
            
            // Búsqueda exacta
            if (stripos($text, $searchTerm) !== false) {
                return true;
            }
            
            // Búsqueda normalizada
            if (stripos($normalizedText, $normalizedTerm) !== false) {
                return true;
            }
            
            // Búsqueda por palabras individuales
            $words = explode(' ', trim($searchTerm));
            foreach ($words as $word) {
                if (strlen($word) > 2) {
                    if (stripos($textLower, mb_strtolower($word, 'UTF-8')) !== false ||
                        stripos($normalizedText, $this->normalizeText($word)) !== false) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}

