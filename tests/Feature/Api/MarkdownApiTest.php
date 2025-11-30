<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class MarkdownApiTest extends TestCase
{
    public function test_convert_markdown_to_html(): void
    {
        $response = $this->postJson('/api/v1/markdown/convert', [
            'markdown' => '# Hello World',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertStringContainsString('<h1>Hello World</h1>', $response->json('result'));
    }

    public function test_convert_with_formatting(): void
    {
        $response = $this->postJson('/api/v1/markdown/convert', [
            'markdown' => 'This is **bold** and *italic*',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('<strong>bold</strong>', $response->json('result'));
        $this->assertStringContainsString('<em>italic</em>', $response->json('result'));
    }

    public function test_convert_full_page(): void
    {
        $response = $this->postJson('/api/v1/markdown/convert', [
            'markdown' => '# Test',
            'full_page' => true,
            'title' => 'My Document',
        ]);

        $response->assertStatus(200);
        $result = $response->json('result');

        $this->assertStringContainsString('<!DOCTYPE html>', $result);
        $this->assertStringContainsString('<title>My Document</title>', $result);
    }

    public function test_validation_requires_markdown(): void
    {
        $response = $this->postJson('/api/v1/markdown/convert', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['markdown']);
    }

    public function test_code_blocks_converted(): void
    {
        $response = $this->postJson('/api/v1/markdown/convert', [
            'markdown' => "```php\necho 'hello';\n```",
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('<pre>', $response->json('result'));
        // Code block has language class
        $this->assertMatchesRegularExpression('/<code[^>]*>/', $response->json('result'));
    }
}
