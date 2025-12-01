<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UuidApiTest extends TestCase
{
    public function test_generate_single_uuid(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate');

        $response->assertOk()
            ->assertJsonStructure(['success', 'uuid'])
            ->assertJson(['success' => true]);

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $response->json('uuid')
        );
    }

    public function test_generate_multiple_uuids(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'count' => 5,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['success', 'uuids', 'count'])
            ->assertJson(['success' => true, 'count' => 5]);

        $this->assertCount(5, $response->json('uuids'));
    }

    public function test_generate_with_uppercase_format(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'format' => 'uppercase',
        ]);

        $response->assertOk();
        $uuid = $response->json('uuid');
        $this->assertEquals(strtoupper($uuid), $uuid);
    }

    public function test_generate_with_no_hyphens_format(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'format' => 'no-hyphens',
        ]);

        $response->assertOk();
        $uuid = $response->json('uuid');
        $this->assertStringNotContainsString('-', $uuid);
        $this->assertEquals(32, strlen($uuid));
    }

    public function test_generate_with_braces_format(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'format' => 'braces',
        ]);

        $response->assertOk();
        $uuid = $response->json('uuid');
        $this->assertStringStartsWith('{', $uuid);
        $this->assertStringEndsWith('}', $uuid);
    }

    public function test_generate_with_urn_format(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'format' => 'urn',
        ]);

        $response->assertOk();
        $uuid = $response->json('uuid');
        $this->assertStringStartsWith('urn:uuid:', $uuid);
    }

    public function test_generate_validates_count_max(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'count' => 200,
        ]);

        $response->assertUnprocessable();
    }

    public function test_generate_validates_format(): void
    {
        $response = $this->postJson('/api/v1/uuid/generate', [
            'format' => 'invalid',
        ]);

        $response->assertUnprocessable();
    }

    public function test_validate_valid_uuid(): void
    {
        $response = $this->postJson('/api/v1/uuid/validate', [
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'version' => 4,
                'variant' => 'RFC 4122',
            ]);
    }

    public function test_validate_invalid_uuid(): void
    {
        $response = $this->postJson('/api/v1/uuid/validate', [
            'uuid' => 'not-a-valid-uuid',
        ]);

        $response->assertOk()
            ->assertJson(['valid' => false]);
    }

    public function test_validate_requires_uuid(): void
    {
        $response = $this->postJson('/api/v1/uuid/validate', []);

        $response->assertUnprocessable();
    }
}
