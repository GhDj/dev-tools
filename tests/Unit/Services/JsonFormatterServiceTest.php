<?php

namespace Tests\Unit\Services;

use App\Services\JsonFormatterService;
use PHPUnit\Framework\TestCase;

class JsonFormatterServiceTest extends TestCase
{
    private JsonFormatterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JsonFormatterService();
    }

    public function test_format_simple_object(): void
    {
        $json = '{"name":"John","age":30}';
        $result = $this->service->format($json);

        $this->assertStringContainsString("\"name\"", $result);
        $this->assertStringContainsString("\"John\"", $result);
        $this->assertGreaterThan(1, substr_count($result, "\n"));
    }

    public function test_format_nested_object(): void
    {
        $json = '{"user":{"name":"John","address":{"city":"NYC"}}}';
        $result = $this->service->format($json);

        $this->assertStringContainsString("user", $result);
        $this->assertStringContainsString("address", $result);
        $this->assertStringContainsString("city", $result);
    }

    public function test_format_array(): void
    {
        $json = '[1,2,3,"four"]';
        $result = $this->service->format($json);

        $this->assertStringContainsString("1", $result);
        $this->assertStringContainsString("\"four\"", $result);
    }

    public function test_format_with_custom_indent(): void
    {
        $json = '{"a":1}';
        $result2 = $this->service->format($json, 2);
        $result4 = $this->service->format($json, 4);

        // 2-space indent should be shorter than 4-space
        $this->assertLessThan(strlen($result4), strlen($result2));
    }

    public function test_format_preserves_unicode(): void
    {
        $json = '{"emoji":"😀","chinese":"中文"}';
        $result = $this->service->format($json);

        $this->assertStringContainsString("😀", $result);
        $this->assertStringContainsString("中文", $result);
    }

    public function test_format_invalid_json_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON');

        $this->service->format('{invalid json}');
    }

    public function test_minify_removes_whitespace(): void
    {
        $json = '{
            "name": "John",
            "age": 30
        }';
        $result = $this->service->minify($json);

        $this->assertEquals('{"name":"John","age":30}', $result);
    }

    public function test_minify_preserves_unicode(): void
    {
        $json = '{"emoji": "😀"}';
        $result = $this->service->minify($json);

        $this->assertStringContainsString("😀", $result);
    }

    public function test_minify_invalid_json_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->minify('{invalid}');
    }

    public function test_validate_valid_json(): void
    {
        $json = '{"name":"John"}';
        $result = $this->service->validate($json);

        $this->assertTrue($result['valid']);
        $this->assertEquals('object', $result['type']);
        $this->assertArrayHasKey('stats', $result);
    }

    public function test_validate_invalid_json(): void
    {
        $json = '{invalid json}';
        $result = $this->service->validate($json);

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_validate_array_type(): void
    {
        $json = '[1, 2, 3]';
        $result = $this->service->validate($json);

        $this->assertTrue($result['valid']);
        $this->assertEquals('array', $result['type']);
    }

    public function test_get_stats_counts_types(): void
    {
        $json = '{"name":"John","age":30,"active":true,"spouse":null,"tags":["a","b"]}';
        $decoded = json_decode($json);
        $stats = $this->service->getStats($decoded);

        $this->assertEquals(1, $stats['objects']);
        $this->assertEquals(1, $stats['arrays']);
        $this->assertEquals(3, $stats['strings']); // name value + 2 tags
        $this->assertEquals(1, $stats['numbers']);
        $this->assertEquals(1, $stats['booleans']);
        $this->assertEquals(1, $stats['nulls']);
    }

    public function test_get_stats_calculates_depth(): void
    {
        $json = '{"a":{"b":{"c":{"d":1}}}}';
        $decoded = json_decode($json);
        $stats = $this->service->getStats($decoded);

        // Depth: root(1) -> a(2) -> b(3) -> c(4) -> d(5)
        $this->assertEquals(5, $stats['max_depth']);
    }

    public function test_repair_removes_trailing_commas(): void
    {
        $json = '{"name":"John","age":30,}';
        $result = $this->service->repair($json);

        $decoded = json_decode($result);
        $this->assertNotNull($decoded);
        $this->assertEquals('John', $decoded->name);
    }

    public function test_repair_array_trailing_comma(): void
    {
        $json = '[1, 2, 3,]';
        $result = $this->service->repair($json);

        $decoded = json_decode($result);
        $this->assertNotNull($decoded);
        $this->assertCount(3, $decoded);
    }

    public function test_repair_unrepairable_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not repair');

        $this->service->repair('completely broken {{{');
    }

    public function test_sort_keys_alphabetically(): void
    {
        $json = '{"zebra":1,"apple":2,"mango":3}';
        $result = $this->service->sortKeys($json);

        $keys = array_keys((array) json_decode($result));
        $this->assertEquals(['apple', 'mango', 'zebra'], $keys);
    }

    public function test_sort_keys_recursive(): void
    {
        $json = '{"z":{"b":1,"a":2},"a":1}';
        $result = $this->service->sortKeys($json, true);

        $decoded = json_decode($result);
        $outerKeys = array_keys((array) $decoded);
        $innerKeys = array_keys((array) $decoded->a);

        $this->assertEquals(['a', 'z'], $outerKeys);
        // Inner object should also be sorted
        $this->assertEquals(['a', 'b'], array_keys((array) $decoded->z));
    }

    public function test_sort_keys_invalid_json_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->service->sortKeys('{invalid}');
    }

    public function test_format_empty_object(): void
    {
        $json = '{}';
        $result = $this->service->format($json);

        $this->assertEquals('{}', trim($result));
    }

    public function test_format_empty_array(): void
    {
        $json = '[]';
        $result = $this->service->format($json);

        $this->assertEquals('[]', trim($result));
    }

    public function test_format_primitive_values(): void
    {
        $this->assertEquals('"hello"', trim($this->service->format('"hello"')));
        $this->assertEquals('123', trim($this->service->format('123')));
        $this->assertEquals('true', trim($this->service->format('true')));
        $this->assertEquals('null', trim($this->service->format('null')));
    }
}
