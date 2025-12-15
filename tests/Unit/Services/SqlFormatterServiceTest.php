<?php

namespace Tests\Unit\Services;

use App\Services\SqlFormatterService;
use PHPUnit\Framework\TestCase;

class SqlFormatterServiceTest extends TestCase
{
    private SqlFormatterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SqlFormatterService();
    }

    public function test_format_simple_select(): void
    {
        $sql = 'SELECT id, name FROM users WHERE active = 1';
        $result = $this->service->format($sql);

        $this->assertStringContainsString("SELECT", $result);
        $this->assertStringContainsString("FROM", $result);
        $this->assertStringContainsString("WHERE", $result);
        // Check it's formatted with newlines
        $this->assertGreaterThan(1, substr_count($result, "\n"));
    }

    public function test_format_join_query(): void
    {
        $sql = 'SELECT u.id, o.total FROM users u JOIN orders o ON u.id = o.user_id';
        $result = $this->service->format($sql);

        $this->assertStringContainsString("JOIN", $result);
        $this->assertStringContainsString("ON", $result);
    }

    public function test_format_complex_query(): void
    {
        $sql = 'SELECT u.id, u.name, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id = o.user_id WHERE u.status = \'active\' GROUP BY u.id, u.name HAVING COUNT(o.id) > 5 ORDER BY order_count DESC LIMIT 10';
        $result = $this->service->format($sql);

        $this->assertStringContainsString("GROUP BY", $result);
        $this->assertStringContainsString("HAVING", $result);
        $this->assertStringContainsString("ORDER BY", $result);
        $this->assertStringContainsString("LIMIT", $result);
    }

    public function test_format_insert_statement(): void
    {
        $sql = "INSERT INTO users (name, email) VALUES ('John', 'john@example.com')";
        $result = $this->service->format($sql);

        $this->assertStringContainsString("INSERT INTO", $result);
        $this->assertStringContainsString("VALUES", $result);
    }

    public function test_format_update_statement(): void
    {
        $sql = "UPDATE users SET name = 'John' WHERE id = 1";
        $result = $this->service->format($sql);

        $this->assertStringContainsString("UPDATE", $result);
        $this->assertStringContainsString("SET", $result);
    }

    public function test_format_delete_statement(): void
    {
        $sql = "DELETE FROM users WHERE id = 1";
        $result = $this->service->format($sql);

        $this->assertStringContainsString("DELETE", $result);
        $this->assertStringContainsString("FROM", $result);
    }

    public function test_compress_removes_extra_whitespace(): void
    {
        $sql = "SELECT   id,   name   FROM   users   WHERE   id = 1";
        $result = $this->service->compress($sql);

        // Compressed should have minimal whitespace
        $this->assertLessThan(strlen($sql), strlen($result));
    }

    public function test_compress_single_line(): void
    {
        $sql = "SELECT\n  id,\n  name\nFROM\n  users";
        $result = $this->service->compress($sql);

        // Should be on single line
        $this->assertEquals(0, substr_count($result, "\n"));
    }

    public function test_highlight_returns_html(): void
    {
        $sql = 'SELECT id FROM users';
        $result = $this->service->highlight($sql);

        // Highlighted version should contain HTML markup
        $this->assertStringContainsString('<', $result);
    }
}
