<?php

namespace App\Services;

class UrlEncoderService
{
    public function encode(string $input, string $mode = 'component'): array
    {
        return match ($mode) {
            'component' => [
                'success' => true,
                'result' => rawurlencode($input),
                'mode' => 'component',
            ],
            'full' => [
                'success' => true,
                'result' => urlencode($input),
                'mode' => 'full',
            ],
            'query' => [
                'success' => true,
                'result' => http_build_query(['q' => $input]),
                'mode' => 'query',
            ],
            default => [
                'success' => false,
                'error' => 'Invalid mode. Supported: component, full, query',
            ],
        };
    }

    public function decode(string $input): array
    {
        return [
            'success' => true,
            'result' => rawurldecode($input),
        ];
    }

    public function parseUrl(string $url): array
    {
        $parsed = parse_url($url);

        if ($parsed === false) {
            return [
                'success' => false,
                'error' => 'Invalid URL format',
            ];
        }

        $result = [
            'success' => true,
            'components' => [],
        ];

        if (isset($parsed['scheme'])) {
            $result['components']['scheme'] = $parsed['scheme'];
        }
        if (isset($parsed['host'])) {
            $result['components']['host'] = $parsed['host'];
        }
        if (isset($parsed['port'])) {
            $result['components']['port'] = $parsed['port'];
        }
        if (isset($parsed['user'])) {
            $result['components']['user'] = $parsed['user'];
        }
        if (isset($parsed['pass'])) {
            $result['components']['pass'] = $parsed['pass'];
        }
        if (isset($parsed['path'])) {
            $result['components']['path'] = $parsed['path'];
        }
        if (isset($parsed['query'])) {
            $result['components']['query'] = $parsed['query'];
            parse_str($parsed['query'], $queryParams);
            $result['components']['query_params'] = $queryParams;
        }
        if (isset($parsed['fragment'])) {
            $result['components']['fragment'] = $parsed['fragment'];
        }

        return $result;
    }

    public function buildUrl(array $components): array
    {
        $url = '';

        if (!empty($components['scheme'])) {
            $url .= $components['scheme'] . '://';
        }

        if (!empty($components['user'])) {
            $url .= $components['user'];
            if (!empty($components['pass'])) {
                $url .= ':' . $components['pass'];
            }
            $url .= '@';
        }

        if (!empty($components['host'])) {
            $url .= $components['host'];
        }

        if (!empty($components['port'])) {
            $url .= ':' . $components['port'];
        }

        if (!empty($components['path'])) {
            $url .= $components['path'];
        }

        if (!empty($components['query'])) {
            $url .= '?' . $components['query'];
        } elseif (!empty($components['query_params']) && is_array($components['query_params'])) {
            $url .= '?' . http_build_query($components['query_params']);
        }

        if (!empty($components['fragment'])) {
            $url .= '#' . $components['fragment'];
        }

        return [
            'success' => true,
            'url' => $url,
        ];
    }
}
