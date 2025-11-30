<?php

namespace Tests\Unit\Services;

use App\Services\YamlConverterService;
use PHPUnit\Framework\TestCase;

class YamlConverterServiceTest extends TestCase
{
    private YamlConverterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new YamlConverterService();
    }

    public function test_yaml_to_json_simple(): void
    {
        $yaml = "name: John\nage: 30";
        $result = $this->service->yamlToJson($yaml);

        $this->assertTrue($result['success']);
        $decoded = json_decode($result['result'], true);
        $this->assertEquals('John', $decoded['name']);
        $this->assertEquals(30, $decoded['age']);
    }

    public function test_yaml_to_json_nested(): void
    {
        $yaml = "person:\n  name: John\n  age: 30";
        $result = $this->service->yamlToJson($yaml);

        $this->assertTrue($result['success']);
        $decoded = json_decode($result['result'], true);
        $this->assertEquals('John', $decoded['person']['name']);
    }

    public function test_yaml_to_json_array(): void
    {
        $yaml = "items:\n  - apple\n  - banana";
        $result = $this->service->yamlToJson($yaml);

        $this->assertTrue($result['success']);
        $decoded = json_decode($result['result'], true);
        $this->assertEquals(['apple', 'banana'], $decoded['items']);
    }

    public function test_yaml_to_json_invalid_yaml(): void
    {
        $yaml = "invalid: yaml: syntax:";
        $result = $this->service->yamlToJson($yaml);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid YAML', $result['error']);
    }

    public function test_json_to_yaml_simple(): void
    {
        $json = '{"name": "John", "age": 30}';
        $result = $this->service->jsonToYaml($json);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('name: John', $result['result']);
        $this->assertStringContainsString('age: 30', $result['result']);
    }

    public function test_json_to_yaml_nested(): void
    {
        $json = '{"person": {"name": "John"}}';
        $result = $this->service->jsonToYaml($json);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('person:', $result['result']);
        $this->assertStringContainsString('name: John', $result['result']);
    }

    public function test_json_to_yaml_invalid_json(): void
    {
        $json = '{invalid json}';
        $result = $this->service->jsonToYaml($json);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Invalid JSON', $result['error']);
    }

    public function test_json_to_yaml_custom_indent(): void
    {
        $json = '{"person": {"name": "John"}}';
        $result = $this->service->jsonToYaml($json, 4);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('    name:', $result['result']);
    }
}
