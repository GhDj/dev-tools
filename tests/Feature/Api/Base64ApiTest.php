<?php

namespace Tests\Feature\Api;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class Base64ApiTest extends TestCase
{
    public function test_encode_text(): void
    {
        $response = $this->postJson('/api/v1/base64/encode', [
            'input' => 'Hello World',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => 'SGVsbG8gV29ybGQ=',
            ]);
    }

    public function test_decode_text(): void
    {
        $response = $this->postJson('/api/v1/base64/decode', [
            'input' => 'SGVsbG8gV29ybGQ=',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => 'Hello World',
                'is_binary' => false,
            ]);
    }

    public function test_decode_invalid_base64(): void
    {
        $response = $this->postJson('/api/v1/base64/decode', [
            'input' => '!!!invalid!!!',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_encode_unicode(): void
    {
        $response = $this->postJson('/api/v1/base64/encode', [
            'input' => 'こんにちは',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => '44GT44KT44Gr44Gh44Gv',
            ]);
    }

    public function test_validation_requires_input_for_encode(): void
    {
        $response = $this->postJson('/api/v1/base64/encode', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['input']);
    }

    public function test_validation_requires_input_for_decode(): void
    {
        $response = $this->postJson('/api/v1/base64/decode', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['input']);
    }

    public function test_encode_file(): void
    {
        $file = UploadedFile::fake()->create('test.txt', 1, 'text/plain');
        file_put_contents($file->getRealPath(), 'Hello World');

        $response = $this->post('/api/v1/base64/encode-file', [
            'file' => $file,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertStringStartsWith('data:text/plain;base64,', $response->json('result'));
    }

    public function test_encode_file_validates_size(): void
    {
        $file = UploadedFile::fake()->create('large.txt', 6000); // 6MB, over 5MB limit

        $response = $this->post('/api/v1/base64/encode-file', [
            'file' => $file,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_encode_file_requires_file(): void
    {
        $response = $this->post('/api/v1/base64/encode-file', [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }
}
