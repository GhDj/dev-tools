<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class CsvApiTest extends TestCase
{
    public function test_convert_csv_to_json(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'csv' => "name,age\nJohn,30\nJane,25",
            'format' => 'json',
            'has_headers' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $result = json_decode($response->json('result'), true);
        $this->assertCount(2, $result);
        $this->assertEquals('John', $result[0]['name']);
    }

    public function test_convert_csv_to_sql(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'csv' => "name,age\nJohn,30",
            'format' => 'sql',
            'table_name' => 'users',
            'has_headers' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertStringContainsString('INSERT INTO `users`', $response->json('result'));
    }

    public function test_convert_csv_to_php(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'csv' => "name,age\nJohn,30",
            'format' => 'php',
            'has_headers' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertStringContainsString("'name' => 'John'", $response->json('result'));
    }

    public function test_validation_requires_csv(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'format' => 'json',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['csv']);
    }

    public function test_validation_requires_valid_format(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'csv' => 'test',
            'format' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['format']);
    }

    public function test_custom_delimiter(): void
    {
        $response = $this->postJson('/api/v1/csv/convert', [
            'csv' => "name;age\nJohn;30",
            'format' => 'json',
            'delimiter' => ';',
            'has_headers' => true,
        ]);

        $response->assertStatus(200);
        $result = json_decode($response->json('result'), true);
        $this->assertEquals('John', $result[0]['name']);
    }
}
