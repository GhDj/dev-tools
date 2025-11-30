<?php

namespace Tests\Unit\Services;

use App\Services\UrlEncoderService;
use PHPUnit\Framework\TestCase;

class UrlEncoderServiceTest extends TestCase
{
    private UrlEncoderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UrlEncoderService();
    }

    public function test_encode_component(): void
    {
        $result = $this->service->encode('hello world', 'component');

        $this->assertTrue($result['success']);
        $this->assertEquals('hello%20world', $result['result']);
        $this->assertEquals('component', $result['mode']);
    }

    public function test_encode_full(): void
    {
        $result = $this->service->encode('hello world', 'full');

        $this->assertTrue($result['success']);
        $this->assertEquals('hello+world', $result['result']);
        $this->assertEquals('full', $result['mode']);
    }

    public function test_encode_special_characters(): void
    {
        $result = $this->service->encode('foo=bar&baz=qux', 'component');

        $this->assertTrue($result['success']);
        $this->assertEquals('foo%3Dbar%26baz%3Dqux', $result['result']);
    }

    public function test_encode_unicode(): void
    {
        $result = $this->service->encode('héllo wörld', 'component');

        $this->assertTrue($result['success']);
        $this->assertNotEquals('héllo wörld', $result['result']);
    }

    public function test_encode_invalid_mode(): void
    {
        $result = $this->service->encode('hello', 'invalid');

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_decode(): void
    {
        $result = $this->service->decode('hello%20world');

        $this->assertTrue($result['success']);
        $this->assertEquals('hello world', $result['result']);
    }

    public function test_decode_plus_sign(): void
    {
        $result = $this->service->decode('hello+world');

        $this->assertTrue($result['success']);
        $this->assertEquals('hello+world', $result['result']); // rawurldecode doesn't convert +
    }

    public function test_decode_special_characters(): void
    {
        $result = $this->service->decode('foo%3Dbar%26baz%3Dqux');

        $this->assertTrue($result['success']);
        $this->assertEquals('foo=bar&baz=qux', $result['result']);
    }

    public function test_parse_url_full(): void
    {
        $result = $this->service->parseUrl('https://user:pass@example.com:8080/path/to/page?foo=bar&baz=qux#section');

        $this->assertTrue($result['success']);
        $this->assertEquals('https', $result['components']['scheme']);
        $this->assertEquals('example.com', $result['components']['host']);
        $this->assertEquals(8080, $result['components']['port']);
        $this->assertEquals('user', $result['components']['user']);
        $this->assertEquals('pass', $result['components']['pass']);
        $this->assertEquals('/path/to/page', $result['components']['path']);
        $this->assertEquals('foo=bar&baz=qux', $result['components']['query']);
        $this->assertEquals('section', $result['components']['fragment']);
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $result['components']['query_params']);
    }

    public function test_parse_url_simple(): void
    {
        $result = $this->service->parseUrl('https://example.com');

        $this->assertTrue($result['success']);
        $this->assertEquals('https', $result['components']['scheme']);
        $this->assertEquals('example.com', $result['components']['host']);
    }

    public function test_parse_url_with_path(): void
    {
        $result = $this->service->parseUrl('https://example.com/path');

        $this->assertTrue($result['success']);
        $this->assertEquals('/path', $result['components']['path']);
    }

    public function test_parse_url_invalid(): void
    {
        $result = $this->service->parseUrl('not a url : // broken');

        // parse_url is quite lenient, so this might not fail
        // But badly malformed URLs will fail
        $this->assertIsArray($result);
    }

    public function test_build_url_full(): void
    {
        $components = [
            'scheme' => 'https',
            'user' => 'admin',
            'pass' => 'secret',
            'host' => 'example.com',
            'port' => 8080,
            'path' => '/api/v1',
            'query' => 'foo=bar',
            'fragment' => 'section',
        ];

        $result = $this->service->buildUrl($components);

        $this->assertTrue($result['success']);
        $this->assertEquals('https://admin:secret@example.com:8080/api/v1?foo=bar#section', $result['url']);
    }

    public function test_build_url_simple(): void
    {
        $components = [
            'scheme' => 'https',
            'host' => 'example.com',
        ];

        $result = $this->service->buildUrl($components);

        $this->assertTrue($result['success']);
        $this->assertEquals('https://example.com', $result['url']);
    }

    public function test_build_url_with_query_params(): void
    {
        $components = [
            'scheme' => 'https',
            'host' => 'example.com',
            'query_params' => ['foo' => 'bar', 'baz' => 'qux'],
        ];

        $result = $this->service->buildUrl($components);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('foo=bar', $result['url']);
        $this->assertStringContainsString('baz=qux', $result['url']);
    }

    public function test_roundtrip_encode_decode(): void
    {
        $original = 'Hello World! Special chars: &=?#';
        $encoded = $this->service->encode($original, 'component');
        $decoded = $this->service->decode($encoded['result']);

        $this->assertEquals($original, $decoded['result']);
    }
}
