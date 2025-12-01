<?php

namespace Tests\Unit\Services;

use App\Services\UuidService;
use PHPUnit\Framework\TestCase;

class UuidServiceTest extends TestCase
{
    private UuidService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UuidService();
    }

    public function test_generate_v4_returns_valid_format(): void
    {
        $uuid = $this->service->generateV4();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );
    }

    public function test_generate_v4_returns_unique_values(): void
    {
        $uuids = [];
        for ($i = 0; $i < 100; $i++) {
            $uuids[] = $this->service->generateV4();
        }

        $this->assertCount(100, array_unique($uuids));
    }

    public function test_generate_bulk_returns_correct_count(): void
    {
        $uuids = $this->service->generateBulk(10);

        $this->assertCount(10, $uuids);
    }

    public function test_generate_bulk_limits_to_100(): void
    {
        $uuids = $this->service->generateBulk(200);

        $this->assertCount(100, $uuids);
    }

    public function test_generate_bulk_all_unique(): void
    {
        $uuids = $this->service->generateBulk(50);

        $this->assertCount(50, array_unique($uuids));
    }

    public function test_validate_valid_v4_uuid(): void
    {
        $result = $this->service->validate('550e8400-e29b-41d4-a716-446655440000');

        $this->assertTrue($result['valid']);
        $this->assertEquals(4, $result['version']);
        $this->assertEquals('RFC 4122', $result['variant']);
    }

    public function test_validate_valid_v1_uuid(): void
    {
        $result = $this->service->validate('6ba7b810-9dad-11d1-80b4-00c04fd430c8');

        $this->assertTrue($result['valid']);
        $this->assertEquals(1, $result['version']);
    }

    public function test_validate_invalid_uuid(): void
    {
        $result = $this->service->validate('not-a-uuid');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_validate_invalid_format(): void
    {
        $result = $this->service->validate('550e8400e29b41d4a716446655440000'); // No hyphens

        $this->assertFalse($result['valid']);
    }

    public function test_format_uppercase(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $result = $this->service->format($uuid, 'uppercase');

        $this->assertEquals('550E8400-E29B-41D4-A716-446655440000', $result);
    }

    public function test_format_lowercase(): void
    {
        $uuid = '550E8400-E29B-41D4-A716-446655440000';
        $result = $this->service->format($uuid, 'lowercase');

        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $result);
    }

    public function test_format_no_hyphens(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $result = $this->service->format($uuid, 'no-hyphens');

        $this->assertEquals('550e8400e29b41d4a716446655440000', $result);
    }

    public function test_format_braces(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $result = $this->service->format($uuid, 'braces');

        $this->assertEquals('{550e8400-e29b-41d4-a716-446655440000}', $result);
    }

    public function test_format_urn(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $result = $this->service->format($uuid, 'urn');

        $this->assertEquals('urn:uuid:550e8400-e29b-41d4-a716-446655440000', $result);
    }

    public function test_format_invalid_uuid_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->format('invalid', 'lowercase');
    }

    public function test_format_invalid_format_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->format('550e8400-e29b-41d4-a716-446655440000', 'invalid-format');
    }
}
