<?php

namespace App\Services;

class UuidService
{
    public function generateV4(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }

    public function generateBulk(int $count): array
    {
        $count = min($count, 100);
        $uuids = [];

        for ($i = 0; $i < $count; $i++) {
            $uuids[] = $this->generateV4();
        }

        return $uuids;
    }

    public function validate(string $uuid): array
    {
        $uuid = trim($uuid);

        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        if (!preg_match($pattern, $uuid)) {
            return [
                'valid' => false,
                'error' => 'Invalid UUID format',
            ];
        }

        $version = (int) $uuid[14];

        return [
            'valid' => true,
            'version' => $version,
            'variant' => 'RFC 4122',
        ];
    }

    public function format(string $uuid, string $format): string
    {
        $clean = strtolower(preg_replace('/[^0-9a-f]/i', '', $uuid));

        if (strlen($clean) !== 32) {
            throw new \InvalidArgumentException('Invalid UUID');
        }

        return match ($format) {
            'uppercase' => strtoupper(sprintf(
                '%s-%s-%s-%s-%s',
                substr($clean, 0, 8),
                substr($clean, 8, 4),
                substr($clean, 12, 4),
                substr($clean, 16, 4),
                substr($clean, 20, 12)
            )),
            'lowercase' => sprintf(
                '%s-%s-%s-%s-%s',
                substr($clean, 0, 8),
                substr($clean, 8, 4),
                substr($clean, 12, 4),
                substr($clean, 16, 4),
                substr($clean, 20, 12)
            ),
            'no-hyphens' => $clean,
            'braces' => sprintf(
                '{%s-%s-%s-%s-%s}',
                substr($clean, 0, 8),
                substr($clean, 8, 4),
                substr($clean, 12, 4),
                substr($clean, 16, 4),
                substr($clean, 20, 12)
            ),
            'urn' => sprintf(
                'urn:uuid:%s-%s-%s-%s-%s',
                substr($clean, 0, 8),
                substr($clean, 8, 4),
                substr($clean, 12, 4),
                substr($clean, 16, 4),
                substr($clean, 20, 12)
            ),
            default => throw new \InvalidArgumentException('Invalid format'),
        };
    }
}
