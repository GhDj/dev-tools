<?php

namespace App\Services;

class Base64Service
{
    public function encode(string $input): string
    {
        return base64_encode($input);
    }

    public function decode(string $input): array
    {
        $decoded = base64_decode($input, true);

        if ($decoded === false) {
            return [
                'success' => false,
                'error' => 'Invalid Base64 string',
            ];
        }

        return [
            'success' => true,
            'result' => $decoded,
            'is_binary' => !$this->isUtf8($decoded),
        ];
    }

    public function encodeFile(string $content, string $mimeType): string
    {
        return 'data:' . $mimeType . ';base64,' . base64_encode($content);
    }

    public function decodeDataUrl(string $dataUrl): array
    {
        if (!preg_match('/^data:([^;]+);base64,(.+)$/', $dataUrl, $matches)) {
            return [
                'success' => false,
                'error' => 'Invalid data URL format',
            ];
        }

        $mimeType = $matches[1];
        $base64 = $matches[2];
        $decoded = base64_decode($base64, true);

        if ($decoded === false) {
            return [
                'success' => false,
                'error' => 'Invalid Base64 data in URL',
            ];
        }

        return [
            'success' => true,
            'mime_type' => $mimeType,
            'content' => $decoded,
            'size' => strlen($decoded),
        ];
    }

    private function isUtf8(string $string): bool
    {
        return mb_check_encoding($string, 'UTF-8') && !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', $string);
    }
}
