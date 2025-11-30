<?php

namespace App\Services;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlConverterService
{
    public function yamlToJson(string $yaml): array
    {
        try {
            $data = Yaml::parse($yaml);
            return [
                'success' => true,
                'result' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ];
        } catch (ParseException $e) {
            return [
                'success' => false,
                'error' => 'Invalid YAML: ' . $e->getMessage(),
            ];
        }
    }

    public function jsonToYaml(string $json, int $indent = 2): array
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON: ' . json_last_error_msg(),
            ];
        }

        try {
            $yaml = Yaml::dump($data, 10, $indent, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
            return [
                'success' => true,
                'result' => $yaml,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Conversion error: ' . $e->getMessage(),
            ];
        }
    }
}
