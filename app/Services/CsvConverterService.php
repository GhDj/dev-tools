<?php

namespace App\Services;

class CsvConverterService
{
    public function parse(string $csv, string $delimiter = ','): array
    {
        $lines = str_getcsv($csv, "\n", '"', '');
        $result = [];

        foreach ($lines as $line) {
            if ($line !== null && trim($line) !== '') {
                $result[] = str_getcsv($line, $delimiter, '"', '\\');
            }
        }

        return $result;
    }

    public function toJson(string $csv, string $delimiter = ',', bool $hasHeaders = true): string
    {
        $rows = $this->parse($csv, $delimiter);

        if (empty($rows)) {
            return '[]';
        }

        if ($hasHeaders && count($rows) > 1) {
            $headers = array_shift($rows);
            $data = [];

            foreach ($rows as $row) {
                $item = [];
                foreach ($headers as $index => $header) {
                    $item[trim($header)] = $row[$index] ?? null;
                }
                $data[] = $item;
            }

            return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function toSql(string $csv, string $tableName, string $delimiter = ',', bool $hasHeaders = true): string
    {
        $rows = $this->parse($csv, $delimiter);

        if (empty($rows)) {
            return '';
        }

        $statements = [];

        if ($hasHeaders && count($rows) > 1) {
            $headers = array_shift($rows);
            $columns = implode(', ', array_map(fn($h) => '`' . trim($h) . '`', $headers));

            foreach ($rows as $row) {
                $values = implode(', ', array_map(function($v) {
                    if ($v === null || $v === '') {
                        return 'NULL';
                    }
                    return "'" . addslashes($v) . "'";
                }, $row));

                $statements[] = "INSERT INTO `{$tableName}` ({$columns}) VALUES ({$values});";
            }
        } else {
            foreach ($rows as $row) {
                $values = implode(', ', array_map(function($v) {
                    if ($v === null || $v === '') {
                        return 'NULL';
                    }
                    return "'" . addslashes($v) . "'";
                }, $row));

                $statements[] = "INSERT INTO `{$tableName}` VALUES ({$values});";
            }
        }

        return implode("\n", $statements);
    }

    public function toPhpArray(string $csv, string $delimiter = ',', bool $hasHeaders = true): string
    {
        $rows = $this->parse($csv, $delimiter);

        if (empty($rows)) {
            return '[]';
        }

        if ($hasHeaders && count($rows) > 1) {
            $headers = array_shift($rows);
            $data = [];

            foreach ($rows as $row) {
                $item = [];
                foreach ($headers as $index => $header) {
                    $item[trim($header)] = $row[$index] ?? null;
                }
                $data[] = $item;
            }

            return $this->formatPhpArray($data);
        }

        return $this->formatPhpArray($rows);
    }

    private function formatPhpArray(array $array, int $indent = 0): string
    {
        $spaces = str_repeat('    ', $indent);
        $innerSpaces = str_repeat('    ', $indent + 1);

        $isAssoc = array_keys($array) !== range(0, count($array) - 1);

        $items = [];
        foreach ($array as $key => $value) {
            $keyStr = $isAssoc ? "'" . addslashes($key) . "' => " : '';

            if (is_array($value)) {
                $items[] = $innerSpaces . $keyStr . $this->formatPhpArray($value, $indent + 1);
            } elseif ($value === null) {
                $items[] = $innerSpaces . $keyStr . 'null';
            } elseif (is_bool($value)) {
                $items[] = $innerSpaces . $keyStr . ($value ? 'true' : 'false');
            } elseif (is_numeric($value)) {
                $items[] = $innerSpaces . $keyStr . $value;
            } else {
                $items[] = $innerSpaces . $keyStr . "'" . addslashes($value) . "'";
            }
        }

        return "[\n" . implode(",\n", $items) . ",\n" . $spaces . "]";
    }
}
