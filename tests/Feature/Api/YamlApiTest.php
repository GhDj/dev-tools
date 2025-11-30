<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class YamlApiTest extends TestCase
{
    public function test_yaml_to_json(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'input' => "name: John\nage: 30",
            'direction' => 'yaml-to-json',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = json_decode($response->json('result'), true);
        $this->assertEquals('John', $result['name']);
        $this->assertEquals(30, $result['age']);
    }

    public function test_json_to_yaml(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'input' => '{"name": "John", "age": 30}',
            'direction' => 'json-to-yaml',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertStringContainsString('name: John', $response->json('result'));
    }

    public function test_invalid_yaml_returns_error(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'input' => "invalid: yaml: syntax:",
            'direction' => 'yaml-to-json',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_invalid_json_returns_error(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'input' => '{invalid}',
            'direction' => 'json-to-yaml',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_validation_requires_input(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'direction' => 'yaml-to-json',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['input']);
    }

    public function test_validation_requires_valid_direction(): void
    {
        $response = $this->postJson('/api/v1/yaml/convert', [
            'input' => 'test',
            'direction' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['direction']);
    }
}
