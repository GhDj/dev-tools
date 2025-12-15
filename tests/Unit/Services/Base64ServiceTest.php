<?php

namespace Tests\Unit\Services;

use App\Services\Base64Service;
use PHPUnit\Framework\TestCase;

class Base64ServiceTest extends TestCase
{
    private Base64Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new Base64Service();
    }

    public function test_encode_simple_string(): void
    {
        $result = $this->service->encode('Hello World');
        $this->assertEquals('SGVsbG8gV29ybGQ=', $result);
    }

    public function test_encode_empty_string(): void
    {
        $result = $this->service->encode('');
        $this->assertEquals('', $result);
    }

    public function test_encode_unicode(): void
    {
        $result = $this->service->encode('こんにちは');
        $this->assertEquals('44GT44KT44Gr44Gh44Gv', $result);
    }

    public function test_encode_special_characters(): void
    {
        $result = $this->service->encode('Hello & <World>');
        $decoded = base64_decode($result);
        $this->assertEquals('Hello & <World>', $decoded);
    }

    public function test_decode_valid_base64(): void
    {
        $result = $this->service->decode('SGVsbG8gV29ybGQ=');

        $this->assertTrue($result['success']);
        $this->assertEquals('Hello World', $result['result']);
        $this->assertFalse($result['is_binary']);
    }

    public function test_decode_invalid_base64(): void
    {
        $result = $this->service->decode('!!!invalid!!!');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid', $result['error']);
    }

    public function test_decode_detects_binary_content(): void
    {
        // Create base64 of binary data (null bytes)
        $binary = base64_encode("\x00\x01\x02\x03");
        $result = $this->service->decode($binary);

        $this->assertTrue($result['success']);
        $this->assertTrue($result['is_binary']);
    }

    public function test_decode_unicode(): void
    {
        $result = $this->service->decode('44GT44KT44Gr44Gh44Gv');

        $this->assertTrue($result['success']);
        $this->assertEquals('こんにちは', $result['result']);
        $this->assertFalse($result['is_binary']);
    }

    public function test_encode_file_creates_data_url(): void
    {
        $content = 'Hello World';
        $mimeType = 'text/plain';

        $result = $this->service->encodeFile($content, $mimeType);

        $this->assertStringStartsWith('data:text/plain;base64,', $result);
        $this->assertStringContainsString('SGVsbG8gV29ybGQ=', $result);
    }

    public function test_encode_file_with_image_mime(): void
    {
        $content = 'fake image data';
        $mimeType = 'image/png';

        $result = $this->service->encodeFile($content, $mimeType);

        $this->assertStringStartsWith('data:image/png;base64,', $result);
    }

    public function test_decode_data_url_valid(): void
    {
        $dataUrl = 'data:text/plain;base64,SGVsbG8gV29ybGQ=';
        $result = $this->service->decodeDataUrl($dataUrl);

        $this->assertTrue($result['success']);
        $this->assertEquals('text/plain', $result['mime_type']);
        $this->assertEquals('Hello World', $result['content']);
        $this->assertEquals(11, $result['size']);
    }

    public function test_decode_data_url_invalid_format(): void
    {
        $result = $this->service->decodeDataUrl('not a data url');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid data URL', $result['error']);
    }

    public function test_decode_data_url_invalid_base64(): void
    {
        $dataUrl = 'data:text/plain;base64,!!!invalid!!!';
        $result = $this->service->decodeDataUrl($dataUrl);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid Base64', $result['error']);
    }

    public function test_roundtrip_encode_decode(): void
    {
        $original = 'Test string with special chars: @#$%^&*()';
        $encoded = $this->service->encode($original);
        $decoded = $this->service->decode($encoded);

        $this->assertTrue($decoded['success']);
        $this->assertEquals($original, $decoded['result']);
    }
}
