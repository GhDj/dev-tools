<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class JsonApiTest extends TestCase
{
    public function test_format_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"name":"John","age":30}',
            'mode' => 'format',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = $response->json('result');
        $this->assertStringContainsString("\"name\"", $result);
        $this->assertGreaterThan(1, substr_count($result, "\n"));
    }

    public function test_minify_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => "{\n  \"name\": \"John\"\n}",
            'mode' => 'minify',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = $response->json('result');
        $this->assertEquals('{"name":"John"}', $result);
    }

    public function test_validate_valid_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"name":"John"}',
            'mode' => 'validate',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'validation' => [
                    'valid' => true,
                    'type' => 'object',
                ],
            ]);

        $this->assertArrayHasKey('stats', $response->json('validation'));
    }

    public function test_validate_invalid_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{invalid json}',
            'mode' => 'validate',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'validation' => [
                    'valid' => false,
                ],
            ]);

        $this->assertArrayHasKey('error', $response->json('validation'));
    }

    public function test_repair_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"name":"John",}',
            'mode' => 'repair',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = json_decode($response->json('result'));
        $this->assertEquals('John', $result->name);
    }

    public function test_sort_keys(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"zebra":1,"apple":2}',
            'mode' => 'sort',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = json_decode($response->json('result'));
        $keys = array_keys((array) $result);
        $this->assertEquals(['apple', 'zebra'], $keys);
    }

    public function test_default_mode_is_format(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"a":1}',
        ]);

        $response->assertStatus(200);
        $this->assertGreaterThan(0, substr_count($response->json('result'), "\n"));
    }

    public function test_custom_indent(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"a":1}',
            'indent' => 2,
        ]);

        $response->assertStatus(200);
        $result = $response->json('result');
        // With 2-space indent, the value line should start with 2 spaces
        $this->assertStringContainsString("  \"a\"", $result);
    }

    public function test_validation_requires_json(): void
    {
        $response = $this->postJson('/api/v1/json/format', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['json']);
    }

    public function test_validation_requires_valid_mode(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{}',
            'mode' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['mode']);
    }

    public function test_invalid_json_returns_error(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{broken',
            'mode' => 'format',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_format_preserves_unicode(): void
    {
        $response = $this->postJson('/api/v1/json/format', [
            'json' => '{"emoji":"😀","arabic":"العربية"}',
        ]);

        $response->assertStatus(200);
        $result = $response->json('result');
        $this->assertStringContainsString('😀', $result);
        $this->assertStringContainsString('العربية', $result);
    }
}
