<?php

namespace Tests\Unit\Services;

use App\Services\HashService;
use PHPUnit\Framework\TestCase;

class HashServiceTest extends TestCase
{
    private HashService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new HashService();
    }

    public function test_hash_md5(): void
    {
        $result = $this->service->hash('hello', 'md5');

        $this->assertTrue($result['success']);
        $this->assertEquals('md5', $result['algorithm']);
        $this->assertEquals('5d41402abc4b2a76b9719d911017c592', $result['hash']);
        $this->assertEquals(5, $result['input_length']);
    }

    public function test_hash_sha1(): void
    {
        $result = $this->service->hash('hello', 'sha1');

        $this->assertTrue($result['success']);
        $this->assertEquals('sha1', $result['algorithm']);
        $this->assertEquals('aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d', $result['hash']);
    }

    public function test_hash_sha256(): void
    {
        $result = $this->service->hash('hello', 'sha256');

        $this->assertTrue($result['success']);
        $this->assertEquals('sha256', $result['algorithm']);
        $this->assertEquals('2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824', $result['hash']);
    }

    public function test_hash_sha384(): void
    {
        $result = $this->service->hash('hello', 'sha384');

        $this->assertTrue($result['success']);
        $this->assertEquals('sha384', $result['algorithm']);
    }

    public function test_hash_sha512(): void
    {
        $result = $this->service->hash('hello', 'sha512');

        $this->assertTrue($result['success']);
        $this->assertEquals('sha512', $result['algorithm']);
    }

    public function test_hash_unsupported_algorithm(): void
    {
        $result = $this->service->hash('hello', 'invalid');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_hash_case_insensitive_algorithm(): void
    {
        $result = $this->service->hash('hello', 'MD5');

        $this->assertTrue($result['success']);
        $this->assertEquals('md5', $result['algorithm']);
    }

    public function test_hash_empty_string(): void
    {
        $result = $this->service->hash('', 'md5');

        $this->assertTrue($result['success']);
        $this->assertEquals('d41d8cd98f00b204e9800998ecf8427e', $result['hash']);
    }

    public function test_hash_unicode(): void
    {
        $result = $this->service->hash('', 'md5');

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['hash']);
    }

    public function test_hash_all(): void
    {
        $result = $this->service->hashAll('hello');

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('hashes', $result);
        $this->assertArrayHasKey('md5', $result['hashes']);
        $this->assertArrayHasKey('sha1', $result['hashes']);
        $this->assertArrayHasKey('sha256', $result['hashes']);
        $this->assertArrayHasKey('sha384', $result['hashes']);
        $this->assertArrayHasKey('sha512', $result['hashes']);
    }

    public function test_verify_matching_hash(): void
    {
        $result = $this->service->verify('hello', '5d41402abc4b2a76b9719d911017c592', 'md5');

        $this->assertTrue($result['success']);
        $this->assertTrue($result['match']);
        $this->assertEquals('md5', $result['algorithm']);
    }

    public function test_verify_non_matching_hash(): void
    {
        $result = $this->service->verify('hello', 'wronghash', 'md5');

        $this->assertTrue($result['success']);
        $this->assertFalse($result['match']);
    }

    public function test_verify_auto_detect_algorithm(): void
    {
        $result = $this->service->verify('hello', '5d41402abc4b2a76b9719d911017c592');

        $this->assertTrue($result['success']);
        $this->assertTrue($result['match']);
        $this->assertEquals('md5', $result['algorithm']);
    }

    public function test_verify_auto_detect_sha256(): void
    {
        $result = $this->service->verify('hello', '2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e73043362938b9824');

        $this->assertTrue($result['success']);
        $this->assertTrue($result['match']);
        $this->assertEquals('sha256', $result['algorithm']);
    }

    public function test_verify_no_match_any_algorithm(): void
    {
        $result = $this->service->verify('hello', 'definitelynotahash');

        $this->assertTrue($result['success']);
        $this->assertFalse($result['match']);
        $this->assertNull($result['algorithm']);
    }

    public function test_verify_unsupported_algorithm(): void
    {
        $result = $this->service->verify('hello', 'hash', 'invalid');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_get_supported_algorithms(): void
    {
        $algorithms = $this->service->getSupportedAlgorithms();

        $this->assertContains('md5', $algorithms);
        $this->assertContains('sha1', $algorithms);
        $this->assertContains('sha256', $algorithms);
        $this->assertContains('sha384', $algorithms);
        $this->assertContains('sha512', $algorithms);
    }
}
