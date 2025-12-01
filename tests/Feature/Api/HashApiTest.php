<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HashApiTest extends TestCase
{
    public function test_hash_all_algorithms(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', [
            'input' => 'hello',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'hashes' => ['md5', 'sha1', 'sha256', 'sha384', 'sha512'],
                'input_length',
            ])
            ->assertJson([
                'success' => true,
                'input_length' => 5,
            ]);
    }

    public function test_hash_specific_algorithm(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', [
            'input' => 'hello',
            'algorithm' => 'md5',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'algorithm' => 'md5',
                'hash' => '5d41402abc4b2a76b9719d911017c592',
            ]);
    }

    public function test_hash_sha256(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', [
            'input' => 'hello',
            'algorithm' => 'sha256',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'algorithm' => 'sha256',
                'hash' => '2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824',
            ]);
    }

    public function test_hash_unsupported_algorithm(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', [
            'input' => 'hello',
            'algorithm' => 'invalid',
        ]);

        $response->assertUnprocessable()
            ->assertJson(['success' => false]);
    }

    public function test_hash_requires_input(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', []);

        $response->assertUnprocessable();
    }

    public function test_hash_empty_string(): void
    {
        $response = $this->postJson('/api/v1/hash/generate', [
            'input' => '',
        ]);

        $response->assertUnprocessable();
    }

    public function test_verify_matching_hash(): void
    {
        $response = $this->postJson('/api/v1/hash/verify', [
            'input' => 'hello',
            'hash' => '5d41402abc4b2a76b9719d911017c592',
            'algorithm' => 'md5',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'match' => true,
                'algorithm' => 'md5',
            ]);
    }

    public function test_verify_non_matching_hash(): void
    {
        $response = $this->postJson('/api/v1/hash/verify', [
            'input' => 'hello',
            'hash' => 'wronghash',
            'algorithm' => 'md5',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'match' => false,
            ]);
    }

    public function test_verify_auto_detect_algorithm(): void
    {
        $response = $this->postJson('/api/v1/hash/verify', [
            'input' => 'hello',
            'hash' => '5d41402abc4b2a76b9719d911017c592',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'match' => true,
                'algorithm' => 'md5',
            ]);
    }

    public function test_verify_requires_input(): void
    {
        $response = $this->postJson('/api/v1/hash/verify', [
            'hash' => 'somehash',
        ]);

        $response->assertUnprocessable();
    }

    public function test_verify_requires_hash(): void
    {
        $response = $this->postJson('/api/v1/hash/verify', [
            'input' => 'hello',
        ]);

        $response->assertUnprocessable();
    }
}
