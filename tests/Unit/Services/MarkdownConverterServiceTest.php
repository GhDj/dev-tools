<?php

namespace Tests\Unit\Services;

use App\Services\MarkdownConverterService;
use PHPUnit\Framework\TestCase;

class MarkdownConverterServiceTest extends TestCase
{
    private MarkdownConverterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MarkdownConverterService();
    }

    public function test_heading(): void
    {
        $result = $this->service->toHtml('# Hello World');
        $this->assertStringContainsString('<h1>Hello World</h1>', $result);
    }

    public function test_multiple_heading_levels(): void
    {
        $result = $this->service->toHtml("# H1\n## H2\n### H3");

        $this->assertStringContainsString('<h1>H1</h1>', $result);
        $this->assertStringContainsString('<h2>H2</h2>', $result);
        $this->assertStringContainsString('<h3>H3</h3>', $result);
    }

    public function test_bold_text(): void
    {
        $result = $this->service->toHtml('This is **bold** text');
        $this->assertStringContainsString('<strong>bold</strong>', $result);
    }

    public function test_italic_text(): void
    {
        $result = $this->service->toHtml('This is *italic* text');
        $this->assertStringContainsString('<em>italic</em>', $result);
    }

    public function test_unordered_list(): void
    {
        $result = $this->service->toHtml("- Item 1\n- Item 2");

        $this->assertStringContainsString('<ul>', $result);
        $this->assertStringContainsString('<li>Item 1</li>', $result);
        $this->assertStringContainsString('<li>Item 2</li>', $result);
    }

    public function test_ordered_list(): void
    {
        $result = $this->service->toHtml("1. First\n2. Second");

        $this->assertStringContainsString('<ol>', $result);
        $this->assertStringContainsString('<li>First</li>', $result);
    }

    public function test_code_block(): void
    {
        $result = $this->service->toHtml("```\ncode here\n```");
        $this->assertStringContainsString('<pre>', $result);
        $this->assertStringContainsString('<code>', $result);
    }

    public function test_inline_code(): void
    {
        $result = $this->service->toHtml('Use `echo` command');
        $this->assertStringContainsString('<code>echo</code>', $result);
    }

    public function test_link(): void
    {
        $result = $this->service->toHtml('[Google](https://google.com)');
        $this->assertStringContainsString('<a href="https://google.com">Google</a>', $result);
    }

    public function test_blockquote(): void
    {
        $result = $this->service->toHtml('> This is a quote');
        $this->assertStringContainsString('<blockquote>', $result);
    }

    public function test_horizontal_rule(): void
    {
        $result = $this->service->toHtml("text\n\n---\n\nmore text");
        $this->assertStringContainsString('<hr', $result);
    }

    public function test_table(): void
    {
        $markdown = "| Name | Age |\n|------|-----|\n| John | 30  |";
        $result = $this->service->toHtml($markdown);

        $this->assertStringContainsString('<table>', $result);
        $this->assertStringContainsString('<th>Name</th>', $result);
        $this->assertStringContainsString('<td>John</td>', $result);
    }

    public function test_full_html_includes_doctype(): void
    {
        $result = $this->service->toFullHtml('# Test');

        $this->assertStringContainsString('<!DOCTYPE html>', $result);
        $this->assertStringContainsString('<html', $result);
        $this->assertStringContainsString('</html>', $result);
    }

    public function test_full_html_includes_title(): void
    {
        $result = $this->service->toFullHtml('# Test', 'My Custom Title');
        $this->assertStringContainsString('<title>My Custom Title</title>', $result);
    }

    public function test_full_html_includes_styles(): void
    {
        $result = $this->service->toFullHtml('# Test');
        $this->assertStringContainsString('<style>', $result);
    }
}
