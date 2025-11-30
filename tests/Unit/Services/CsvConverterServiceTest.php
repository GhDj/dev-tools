<?php

namespace Tests\Unit\Services;

use App\Services\CsvConverterService;
use PHPUnit\Framework\TestCase;

class CsvConverterServiceTest extends TestCase
{
    private CsvConverterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CsvConverterService();
    }

    public function test_parse_simple_csv(): void
    {
        $csv = "name,age\nJohn,30\nJane,25";
        $result = $this->service->parse($csv);

        $this->assertCount(3, $result);
        $this->assertEquals(['name', 'age'], $result[0]);
        $this->assertEquals(['John', '30'], $result[1]);
        $this->assertEquals(['Jane', '25'], $result[2]);
    }

    public function test_parse_with_semicolon_delimiter(): void
    {
        $csv = "name;age\nJohn;30";
        $result = $this->service->parse($csv, ';');

        $this->assertCount(2, $result);
        $this->assertEquals(['name', 'age'], $result[0]);
        $this->assertEquals(['John', '30'], $result[1]);
    }

    public function test_to_json_with_headers(): void
    {
        $csv = "name,age\nJohn,30\nJane,25";
        $result = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($result, true);

        $this->assertCount(2, $decoded);
        $this->assertEquals('John', $decoded[0]['name']);
        $this->assertEquals('30', $decoded[0]['age']);
        $this->assertEquals('Jane', $decoded[1]['name']);
    }

    public function test_to_json_without_headers(): void
    {
        $csv = "John,30\nJane,25";
        $result = $this->service->toJson($csv, ',', false);
        $decoded = json_decode($result, true);

        $this->assertCount(2, $decoded);
        $this->assertEquals(['John', '30'], $decoded[0]);
    }

    public function test_to_json_empty_csv(): void
    {
        $result = $this->service->toJson('', ',', true);
        $this->assertEquals('[]', $result);
    }

    public function test_to_sql_with_headers(): void
    {
        $csv = "name,age\nJohn,30";
        $result = $this->service->toSql($csv, 'users', ',', true);

        $this->assertStringContainsString('INSERT INTO `users`', $result);
        $this->assertStringContainsString('`name`', $result);
        $this->assertStringContainsString('`age`', $result);
        $this->assertStringContainsString("'John'", $result);
        $this->assertStringContainsString("'30'", $result);
    }

    public function test_to_sql_escapes_quotes(): void
    {
        $csv = "name\nO'Brien";
        $result = $this->service->toSql($csv, 'users', ',', true);

        $this->assertStringContainsString("O\\'Brien", $result);
    }

    public function test_to_sql_handles_null_values(): void
    {
        $csv = "name,age\nJohn,";
        $result = $this->service->toSql($csv, 'users', ',', true);

        $this->assertStringContainsString('NULL', $result);
    }

    public function test_to_php_array_with_headers(): void
    {
        $csv = "name,age\nJohn,30";
        $result = $this->service->toPhpArray($csv, ',', true);

        $this->assertStringContainsString("'name' => 'John'", $result);
        // Numeric values are output as numbers, not strings
        $this->assertStringContainsString("'age' => 30", $result);
    }

    public function test_to_php_array_without_headers(): void
    {
        $csv = "John,30";
        $result = $this->service->toPhpArray($csv, ',', false);

        $this->assertStringContainsString("'John'", $result);
        // Numeric values are output as numbers
        $this->assertStringContainsString("30", $result);
    }
}
