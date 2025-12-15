<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class SqlApiTest extends TestCase
{
    public function test_format_sql(): void
    {
        $response = $this->postJson('/api/v1/sql/format', [
            'sql' => 'SELECT id, name FROM users WHERE active = 1',
            'mode' => 'format',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = $response->json('result');
        $this->assertStringContainsString("SELECT", $result);
        $this->assertGreaterThan(1, substr_count($result, "\n"));
    }

    public function test_compress_sql(): void
    {
        $response = $this->postJson('/api/v1/sql/format', [
            'sql' => "SELECT\n  id,\n  name\nFROM\n  users",
            'mode' => 'compress',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = $response->json('result');
        $this->assertEquals(0, substr_count($result, "\n"));
    }

    public function test_highlight_sql(): void
    {
        $response = $this->postJson('/api/v1/sql/format', [
            'sql' => 'SELECT id FROM users',
            'mode' => 'highlight',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        // Highlighted should contain HTML
        $this->assertStringContainsString('<', $response->json('result'));
    }

    public function test_default_mode_is_format(): void
    {
        $response = $this->postJson('/api/v1/sql/format', [
            'sql' => 'SELECT id FROM users',
        ]);

        $response->assertStatus(200);
        // Should be formatted (has newlines)
        $this->assertGreaterThan(0, substr_count($response->json('result'), "\n"));
    }

    public function test_validation_requires_sql(): void
    {
        $response = $this->postJson('/api/v1/sql/format', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sql']);
    }

    public function test_validation_requires_valid_mode(): void
    {
        $response = $this->postJson('/api/v1/sql/format', [
            'sql' => 'SELECT 1',
            'mode' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['mode']);
    }
}
