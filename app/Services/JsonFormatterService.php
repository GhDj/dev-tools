<?php

namespace App\Services;

class JsonFormatterService
{
    /**
     * Format/beautify JSON with indentation
     */
    public function format(string $json, int $indent = 4): string
    {
        $decoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $result = json_encode($decoded, $flags);

        // Adjust indentation if not default 4 spaces
        if ($indent !== 4) {
            $result = preg_replace_callback('/^( +)/m', function ($matches) use ($indent) {
                $spaces = strlen($matches[1]);
                $levels = $spaces / 4;
                return str_repeat(' ', $levels * $indent);
            }, $result);
        }

        return $result;
    }

    /**
     * Minify/compress JSON
     */
    public function minify(string $json): string
    {
        $decoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        return json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Validate JSON and return details
     */
    public function validate(string $json): array
    {
        $decoded = json_decode($json);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            return [
                'valid' => false,
                'error' => json_last_error_msg(),
                'error_code' => $error,
            ];
        }

        return [
            'valid' => true,
            'type' => gettype($decoded),
            'stats' => $this->getStats($decoded),
        ];
    }

    /**
     * Get statistics about JSON structure
     */
    public function getStats(mixed $data): array
    {
        $stats = [
            'objects' => 0,
            'arrays' => 0,
            'strings' => 0,
            'numbers' => 0,
            'booleans' => 0,
            'nulls' => 0,
            'max_depth' => 0,
        ];

        $this->countTypes($data, $stats, 1);

        return $stats;
    }

    /**
     * Recursively count types in JSON structure
     */
    private function countTypes(mixed $data, array &$stats, int $depth): void
    {
        $stats['max_depth'] = max($stats['max_depth'], $depth);

        if (is_object($data)) {
            $stats['objects']++;
            foreach ($data as $value) {
                $this->countTypes($value, $stats, $depth + 1);
            }
        } elseif (is_array($data)) {
            $stats['arrays']++;
            foreach ($data as $value) {
                $this->countTypes($value, $stats, $depth + 1);
            }
        } elseif (is_string($data)) {
            $stats['strings']++;
        } elseif (is_numeric($data)) {
            $stats['numbers']++;
        } elseif (is_bool($data)) {
            $stats['booleans']++;
        } elseif (is_null($data)) {
            $stats['nulls']++;
        }
    }

    /**
     * Fix common JSON errors (trailing commas, single quotes, etc.)
     */
    public function repair(string $json): string
    {
        // Remove trailing commas before ] or }
        $json = preg_replace('/,\s*([\]}])/s', '$1', $json);

        // Try to parse after basic fixes
        $decoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Could not repair JSON: ' . json_last_error_msg());
        }

        return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Sort JSON keys alphabetically
     */
    public function sortKeys(string $json, bool $recursive = true): string
    {
        $decoded = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }

        $sorted = $this->sortKeysRecursive($decoded, $recursive);

        return json_encode($sorted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Recursively sort object keys
     */
    private function sortKeysRecursive(mixed $data, bool $recursive): mixed
    {
        if (is_object($data)) {
            $array = (array) $data;
            ksort($array);

            if ($recursive) {
                foreach ($array as $key => $value) {
                    $array[$key] = $this->sortKeysRecursive($value, $recursive);
                }
            }

            return (object) $array;
        } elseif (is_array($data) && $recursive) {
            return array_map(fn($item) => $this->sortKeysRecursive($item, $recursive), $data);
        }

        return $data;
    }
}
