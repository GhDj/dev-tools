<?php

namespace App\Services;

class HashService
{
    private const SUPPORTED_ALGORITHMS = [
        'md5',
        'sha1',
        'sha256',
        'sha384',
        'sha512',
    ];

    public function hash(string $input, string $algorithm): array
    {
        $algorithm = strtolower($algorithm);

        if (!in_array($algorithm, self::SUPPORTED_ALGORITHMS)) {
            return [
                'success' => false,
                'error' => 'Unsupported algorithm. Supported: ' . implode(', ', self::SUPPORTED_ALGORITHMS),
            ];
        }

        return [
            'success' => true,
            'algorithm' => $algorithm,
            'hash' => hash($algorithm, $input),
            'input_length' => strlen($input),
        ];
    }

    public function hashAll(string $input): array
    {
        $results = [];

        foreach (self::SUPPORTED_ALGORITHMS as $algorithm) {
            $results[$algorithm] = hash($algorithm, $input);
        }

        return [
            'success' => true,
            'hashes' => $results,
            'input_length' => strlen($input),
        ];
    }

    public function verify(string $input, string $hash, ?string $algorithm = null): array
    {
        $hash = strtolower(trim($hash));

        if ($algorithm) {
            $algorithm = strtolower($algorithm);
            if (!in_array($algorithm, self::SUPPORTED_ALGORITHMS)) {
                return [
                    'success' => false,
                    'error' => 'Unsupported algorithm',
                ];
            }

            $computed = hash($algorithm, $input);
            return [
                'success' => true,
                'match' => hash_equals($computed, $hash),
                'algorithm' => $algorithm,
            ];
        }

        foreach (self::SUPPORTED_ALGORITHMS as $algo) {
            $computed = hash($algo, $input);
            if (hash_equals($computed, $hash)) {
                return [
                    'success' => true,
                    'match' => true,
                    'algorithm' => $algo,
                ];
            }
        }

        return [
            'success' => true,
            'match' => false,
            'algorithm' => null,
        ];
    }

    public function getSupportedAlgorithms(): array
    {
        return self::SUPPORTED_ALGORITHMS;
    }
}
