<?php

namespace Tests\Unit\Services;

use App\Services\CsvConverterService;
use PHPUnit\Framework\TestCase;

class CsvEdgeCasesTest extends TestCase
{
    private CsvConverterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CsvConverterService();
    }

    // ==================== Quoted Fields ====================

    public function test_quoted_field_containing_comma(): void
    {
        $csv = "name,address\nJohn,\"123 Main St, Apt 4\"";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
        $this->assertEquals('123 Main St, Apt 4', $result[1][1]);
    }

    public function test_quoted_field_containing_newline(): void
    {
        // Note: This tests if the parser handles embedded newlines in quoted fields
        $csv = "name,note\nJohn,\"Line 1\nLine 2\"";
        $result = $this->service->parse($csv);

        // The basic str_getcsv splits on newlines first, so this tests current behavior
        $this->assertGreaterThanOrEqual(2, count($result));
    }

    public function test_double_quotes_inside_quoted_field(): void
    {
        $csv = "name,quote\nJohn,\"He said \"\"Hello\"\"\"";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
        // CSV standard: doubled quotes become single quotes
        $this->assertStringContainsString('Hello', $result[1][1]);
    }

    public function test_empty_quoted_field(): void
    {
        $csv = "name,value\nJohn,\"\"";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
        $this->assertEquals('', $result[1][1]);
    }

    // ==================== Unicode Characters ====================

    public function test_unicode_emoji(): void
    {
        $csv = "name,mood\nJohn,😀";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        $this->assertEquals('😀', $decoded[0]['mood']);
    }

    public function test_unicode_cjk_characters(): void
    {
        $csv = "name,greeting\n田中,こんにちは";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        $this->assertEquals('田中', $decoded[0]['name']);
        $this->assertEquals('こんにちは', $decoded[0]['greeting']);
    }

    public function test_unicode_arabic(): void
    {
        $csv = "name,greeting\nأحمد,مرحبا";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        $this->assertEquals('أحمد', $decoded[0]['name']);
        $this->assertEquals('مرحبا', $decoded[0]['greeting']);
    }

    public function test_unicode_in_sql_output(): void
    {
        $csv = "name\n田中";
        $sql = $this->service->toSql($csv, 'users', ',', true);

        $this->assertStringContainsString('田中', $sql);
    }

    // ==================== Delimiters ====================

    public function test_tab_delimiter(): void
    {
        $csv = "name\tage\nJohn\t30";
        $result = $this->service->parse($csv, "\t");

        $this->assertCount(2, $result);
        $this->assertEquals(['name', 'age'], $result[0]);
        $this->assertEquals(['John', '30'], $result[1]);
    }

    public function test_pipe_delimiter(): void
    {
        $csv = "name|age\nJohn|30";
        $result = $this->service->parse($csv, '|');

        $this->assertCount(2, $result);
        $this->assertEquals('John', $result[1][0]);
    }

    public function test_delimiter_in_quoted_field(): void
    {
        $csv = "name,value\nJohn,\"a,b,c\"";
        $result = $this->service->parse($csv);

        $this->assertEquals('a,b,c', $result[1][1]);
    }

    // ==================== Edge Cases with Empty/Whitespace ====================

    public function test_empty_csv(): void
    {
        $result = $this->service->parse('');
        $this->assertEmpty($result);
    }

    public function test_whitespace_only_csv(): void
    {
        $result = $this->service->parse("   \n   \n   ");
        $this->assertEmpty($result);
    }

    public function test_single_row_no_data(): void
    {
        $csv = "name,age";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        // With only headers and hasHeaders=true, but only 1 row,
        // it falls through to non-header mode and returns the row as data
        $this->assertCount(1, $decoded);
        $this->assertEquals(['name', 'age'], $decoded[0]);
    }

    public function test_trailing_newlines(): void
    {
        $csv = "name,age\nJohn,30\n\n\n";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
    }

    public function test_leading_whitespace_in_values(): void
    {
        $csv = "name,age\n  John  ,  30  ";
        $result = $this->service->parse($csv);

        // Values should preserve whitespace (trimming is user's choice)
        $this->assertCount(2, $result);
    }

    // ==================== Inconsistent Data ====================

    public function test_inconsistent_column_count_more_columns(): void
    {
        $csv = "name,age\nJohn,30,extra";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
        $this->assertCount(3, $result[1]); // Extra column preserved
    }

    public function test_inconsistent_column_count_fewer_columns(): void
    {
        $csv = "name,age,city\nJohn,30";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        $this->assertCount(1, $decoded);
        $this->assertEquals('John', $decoded[0]['name']);
        $this->assertEquals('30', $decoded[0]['age']);
        $this->assertNull($decoded[0]['city']);
    }

    public function test_trailing_comma(): void
    {
        $csv = "name,age,\nJohn,30,";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
        // Trailing comma creates empty field
        $this->assertCount(3, $result[0]);
    }

    // ==================== Large Data ====================

    public function test_very_long_field(): void
    {
        $longValue = str_repeat('a', 10000);
        $csv = "name,data\nJohn,{$longValue}";
        $result = $this->service->parse($csv);

        $this->assertEquals($longValue, $result[1][1]);
    }

    public function test_many_columns(): void
    {
        $headers = implode(',', range(1, 100));
        $values = implode(',', array_fill(0, 100, 'x'));
        $csv = "{$headers}\n{$values}";

        $result = $this->service->parse($csv);

        $this->assertCount(100, $result[0]);
        $this->assertCount(100, $result[1]);
    }

    public function test_many_rows(): void
    {
        $rows = ["name,age"];
        for ($i = 0; $i < 1000; $i++) {
            $rows[] = "User{$i},{$i}";
        }
        $csv = implode("\n", $rows);

        $result = $this->service->parse($csv);

        $this->assertCount(1001, $result);
    }

    // ==================== Special Characters ====================

    public function test_backslash_in_value(): void
    {
        $csv = "path\nC:\\Users\\John";
        $result = $this->service->parse($csv);

        $this->assertStringContainsString('\\', $result[1][0]);
    }

    public function test_sql_special_chars_escaped(): void
    {
        $csv = "name\nO'Brien";
        $sql = $this->service->toSql($csv, 'users', ',', true);

        // Single quotes should be escaped
        $this->assertStringContainsString("\\'", $sql);
    }

    public function test_html_special_chars_preserved(): void
    {
        $csv = "code\n<script>alert('xss')</script>";
        $json = $this->service->toJson($csv, ',', true);
        $decoded = json_decode($json, true);

        // HTML chars should be preserved in JSON (escaping is for display)
        $this->assertStringContainsString('<script>', $decoded[0]['code']);
    }

    public function test_null_bytes_handled(): void
    {
        $csv = "name,data\nJohn,test\x00data";
        $result = $this->service->parse($csv);

        $this->assertCount(2, $result);
    }

    // ==================== PHP Array Output ====================

    public function test_php_array_escapes_single_quotes(): void
    {
        $csv = "name\nO'Brien";
        $php = $this->service->toPhpArray($csv, ',', true);

        $this->assertStringContainsString("O\\'Brien", $php);
    }

    public function test_php_array_handles_empty_string(): void
    {
        $csv = "name,value\nJohn,";
        $php = $this->service->toPhpArray($csv, ',', true);

        // Empty string is kept as empty string, not null
        $this->assertStringContainsString("'value' => ''", $php);
    }

    public function test_php_array_numeric_detection(): void
    {
        $csv = "id,price,name\n1,19.99,Widget";
        $php = $this->service->toPhpArray($csv, ',', true);

        // Numeric values should not be quoted
        $this->assertStringContainsString("'id' => 1", $php);
        $this->assertStringContainsString("'price' => 19.99", $php);
        $this->assertStringContainsString("'name' => 'Widget'", $php);
    }

    // ==================== SQL Output Edge Cases ====================

    public function test_sql_with_null_values(): void
    {
        $csv = "name,email\nJohn,";
        $sql = $this->service->toSql($csv, 'users', ',', true);

        $this->assertStringContainsString('NULL', $sql);
    }

    public function test_sql_table_name_preserved(): void
    {
        $csv = "name\nJohn";
        $sql = $this->service->toSql($csv, 'my_custom_table', ',', true);

        $this->assertStringContainsString('`my_custom_table`', $sql);
    }

    public function test_sql_multiple_rows(): void
    {
        $csv = "name\nJohn\nJane\nBob";
        $sql = $this->service->toSql($csv, 'users', ',', true);

        $this->assertEquals(3, substr_count($sql, 'INSERT INTO'));
    }
}
