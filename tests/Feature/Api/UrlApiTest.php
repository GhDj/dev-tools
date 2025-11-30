<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlApiTest extends TestCase
{
    public function test_encode_default_mode(): void
    {
        $response = $this->postJson('/api/v1/url/encode', [
            'input' => 'hello world',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'result' => 'hello%20world',
                'mode' => 'component',
            ]);
    }

    public function test_encode_component_mode(): void
    {
        $response = $this->postJson('/api/v1/url/encode', [
            'input' => 'foo=bar&baz',
            'mode' => 'component',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'result' => 'foo%3Dbar%26baz',
            ]);
    }

    public function test_encode_full_mode(): void
    {
        $response = $this->postJson('/api/v1/url/encode', [
            'input' => 'hello world',
            'mode' => 'full',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'result' => 'hello+world',
                'mode' => 'full',
            ]);
    }

    public function test_encode_invalid_mode(): void
    {
        $response = $this->postJson('/api/v1/url/encode', [
            'input' => 'hello',
            'mode' => 'invalid',
        ]);

        $response->assertUnprocessable();
    }

    public function test_encode_requires_input(): void
    {
        $response = $this->postJson('/api/v1/url/encode', []);

        $response->assertUnprocessable();
    }

    public function test_decode(): void
    {
        $response = $this->postJson('/api/v1/url/decode', [
            'input' => 'hello%20world',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'result' => 'hello world',
            ]);
    }

    public function test_decode_special_characters(): void
    {
        $response = $this->postJson('/api/v1/url/decode', [
            'input' => 'foo%3Dbar%26baz%3Dqux',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'result' => 'foo=bar&baz=qux',
            ]);
    }

    public function test_decode_requires_input(): void
    {
        $response = $this->postJson('/api/v1/url/decode', []);

        $response->assertUnprocessable();
    }

    public function test_parse_full_url(): void
    {
        $response = $this->postJson('/api/v1/url/parse', [
            'url' => 'https://user:pass@example.com:8080/path?foo=bar#section',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'components' => [
                    'scheme' => 'https',
                    'host' => 'example.com',
                    'port' => 8080,
                    'user' => 'user',
                    'pass' => 'pass',
                    'path' => '/path',
                    'query' => 'foo=bar',
                    'fragment' => 'section',
                ],
            ]);
    }

    public function test_parse_simple_url(): void
    {
        $response = $this->postJson('/api/v1/url/parse', [
            'url' => 'https://example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'components' => [
                    'scheme' => 'https',
                    'host' => 'example.com',
                ],
            ]);
    }

    public function test_parse_extracts_query_params(): void
    {
        $response = $this->postJson('/api/v1/url/parse', [
            'url' => 'https://example.com?foo=bar&baz=qux',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'components' => [
                    'query_params' => [
                        'foo' => 'bar',
                        'baz' => 'qux',
                    ],
                ],
            ]);
    }

    public function test_parse_requires_url(): void
    {
        $response = $this->postJson('/api/v1/url/parse', []);

        $response->assertUnprocessable();
    }
}
