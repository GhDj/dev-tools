<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Developer Tools');
    }

    public function test_home_page_displays_all_tools(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CSV Converter');
        $response->assertSee('YAML/JSON Converter');
        $response->assertSee('Markdown Preview');
        $response->assertSee('SQL Formatter');
        $response->assertSee('Base64 Encoder');
        $response->assertSee('UUID Generator');
        $response->assertSee('Hash Generator');
        $response->assertSee('URL Encoder');
        $response->assertSee('Code Editor');
        $response->assertSee('Regex Tester');

        $response->assertSee('Base Converter');
        $response->assertSee('Slug Generator');
        $response->assertSee('Color Picker');

        $response->assertSee('QR Code Generator');

        $response->assertSee('HTML Entity Encoder');
        $response->assertSee('Text Case Converter');
        $response->assertSee('Password Generator');
        $response->assertSee('Lorem Ipsum Generator');
    }

    public function test_home_page_has_tool_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="' . route('tools.csv') . '"', false);
        $response->assertSee('href="' . route('tools.yaml') . '"', false);
        $response->assertSee('href="' . route('tools.markdown') . '"', false);
        $response->assertSee('href="' . route('tools.sql') . '"', false);
        $response->assertSee('href="' . route('tools.base64') . '"', false);
        $response->assertSee('href="' . route('tools.uuid') . '"', false);
        $response->assertSee('href="' . route('tools.hash') . '"', false);
        $response->assertSee('href="' . route('tools.url') . '"', false);
        $response->assertSee('href="' . route('tools.code-editor') . '"', false);
        $response->assertSee('href="' . route('tools.regex') . '"', false);

        $response->assertSee('href="' . route('tools.base-converter') . '"', false);
        $response->assertSee('href="' . route('tools.slug-generator') . '"', false);
        $response->assertSee('href="' . route('tools.color-picker') . '"', false);

        $response->assertSee('href="' . route('tools.qr-code') . '"', false);

        $response->assertSee('href="' . route('tools.html-entity') . '"', false);
        $response->assertSee('href="' . route('tools.text-case') . '"', false);
        $response->assertSee('href="' . route('tools.password') . '"', false);
        $response->assertSee('href="' . route('tools.lorem') . '"', false);
    }

    public function test_csv_tool_page_loads(): void
    {
        $response = $this->get('/tools/csv');

        $response->assertStatus(200);
        $response->assertSee('CSV Converter');
        $response->assertSee('Convert CSV to JSON, SQL, or PHP arrays');
    }

    public function test_csv_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/csv');

        $response->assertStatus(200);
        // Has format options
        $response->assertSee('JSON');
        $response->assertSee('SQL INSERT');
        $response->assertSee('PHP Array');
        // Has delimiter options
        $response->assertSee('Comma');
        $response->assertSee('Semicolon');
        // Has back link
        $response->assertSee('Back');
    }

    public function test_yaml_tool_page_loads(): void
    {
        $response = $this->get('/tools/yaml');

        $response->assertStatus(200);
        $response->assertSee('YAML/JSON Converter');
        $response->assertSee('Convert between YAML and JSON');
    }

    public function test_yaml_tool_has_direction_buttons(): void
    {
        $response = $this->get('/tools/yaml');

        $response->assertStatus(200);
        $response->assertSee('YAML');
        $response->assertSee('JSON');
    }

    public function test_markdown_tool_page_loads(): void
    {
        $response = $this->get('/tools/markdown');

        $response->assertStatus(200);
        $response->assertSee('Markdown Preview');
        $response->assertSee('Write Markdown and preview it as HTML');
    }

    public function test_markdown_tool_has_preview_area(): void
    {
        $response = $this->get('/tools/markdown');

        $response->assertStatus(200);
        $response->assertSee('Markdown Input');
        $response->assertSee('Preview');
    }

    public function test_sql_tool_page_loads(): void
    {
        $response = $this->get('/tools/sql');

        $response->assertStatus(200);
        $response->assertSee('SQL Formatter');
        $response->assertSee('Format, beautify, or compress SQL queries');
    }

    public function test_sql_tool_has_action_buttons(): void
    {
        $response = $this->get('/tools/sql');

        $response->assertStatus(200);
        $response->assertSee('Format');
        $response->assertSee('Compress');
    }

    public function test_base64_tool_page_loads(): void
    {
        $response = $this->get('/tools/base64');

        $response->assertStatus(200);
        $response->assertSee('Base64 Encoder/Decoder');
        $response->assertSee('Encode or decode text and files');
    }

    public function test_base64_tool_has_mode_toggle(): void
    {
        $response = $this->get('/tools/base64');

        $response->assertStatus(200);
        $response->assertSee('Text');
        $response->assertSee('File Upload');
    }

    public function test_base64_tool_has_encode_decode_buttons(): void
    {
        $response = $this->get('/tools/base64');

        $response->assertStatus(200);
        $response->assertSee('Encode');
        $response->assertSee('Decode');
    }

    public function test_uuid_tool_page_loads(): void
    {
        $response = $this->get('/tools/uuid');

        $response->assertStatus(200);
        $response->assertSee('UUID Generator');
        $response->assertSee('Generate, validate, and format UUIDs');
    }

    public function test_uuid_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/uuid');

        $response->assertStatus(200);
        $response->assertSee('Generate UUID');
        $response->assertSee('Validate');
        $response->assertSee('Format');
    }

    public function test_hash_tool_page_loads(): void
    {
        $response = $this->get('/tools/hash');

        $response->assertStatus(200);
        $response->assertSee('Hash Generator');
        $response->assertSee('MD5');
        $response->assertSee('SHA-256');
    }

    public function test_hash_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/hash');

        $response->assertStatus(200);
        $response->assertSee('Generate All Hashes');
        $response->assertSee('Verify');
    }

    public function test_url_tool_page_loads(): void
    {
        $response = $this->get('/tools/url');

        $response->assertStatus(200);
        $response->assertSee('URL Encoder/Decoder');
        $response->assertSee('Encode, decode, and parse URLs');
    }

    public function test_url_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/url');

        $response->assertStatus(200);
        $response->assertSee('Encode');
        $response->assertSee('Decode');
        $response->assertSee('Parse URL');
    }

    public function test_code_editor_page_loads(): void
    {
        $response = $this->get('/tools/code-editor');

        $response->assertStatus(200);
        $response->assertSee('Dev Tools');
        $response->assertSee('Online Code Editor');
    }

    public function test_code_editor_has_required_elements(): void
    {
        $response = $this->get('/tools/code-editor');

        $response->assertStatus(200);
        $response->assertSee('index.html');
        $response->assertSee('style.css');
        $response->assertSee('script.js');
        $response->assertSee('Preview');
        $response->assertSee('monaco-container');
    }

    public function test_regex_tool_page_loads(): void
    {
        $response = $this->get('/tools/regex');

        $response->assertStatus(200);
        $response->assertSee('Regex Tester');
        $response->assertSee('Test and debug regular expressions');
    }

    public function test_regex_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/regex');

        $response->assertStatus(200);
        $response->assertSee('Regular Expression');
        $response->assertSee('Test String');
        $response->assertSee('Common Patterns');
        $response->assertSee('Match Details');
    }


    public function test_base_converter_page_loads(): void
    {
        $response = $this->get('/tools/base-converter');

        $response->assertStatus(200);
        $response->assertSee('Base Converter');
        $response->assertSee('Convert between binary, octal, decimal, and hexadecimal');
    }

    public function test_base_converter_has_required_elements(): void
    {
        $response = $this->get('/tools/base-converter');

        $response->assertStatus(200);
        $response->assertSee('Binary');
        $response->assertSee('Octal');
        $response->assertSee('Decimal');
        $response->assertSee('Hexadecimal');
    }

    public function test_slug_generator_page_loads(): void
    {
        $response = $this->get('/tools/slug-generator');

        $response->assertStatus(200);
        $response->assertSee('Slug Generator');
        $response->assertSee('Convert text to URL-friendly slugs');
    }

    public function test_slug_generator_has_required_elements(): void
    {
        $response = $this->get('/tools/slug-generator');

        $response->assertStatus(200);
        $response->assertSee('Input Text');
        $response->assertSee('Generated Slug');
        $response->assertSee('Separator');
        $response->assertSee('Hyphen');
    }

    public function test_color_picker_page_loads(): void
    {
        $response = $this->get('/tools/color-picker');

        $response->assertStatus(200);
        $response->assertSee('Color Picker');
        $response->assertSee('Convert between HEX, RGB, HSL, and CMYK');
    }

    public function test_color_picker_has_required_elements(): void
    {
        $response = $this->get('/tools/color-picker');

        $response->assertStatus(200);
        $response->assertSee('HEX');
        $response->assertSee('RGB');
        $response->assertSee('HSL');
        $response->assertSee('CMYK');
        $response->assertSee('Color Harmony');

    public function test_qr_code_tool_page_loads(): void
    {
        $response = $this->get('/tools/qr-code');

        $response->assertStatus(200);
        $response->assertSee('QR Code Generator');
        $response->assertSee('Generate QR codes for URLs, text, and more');
    }

    public function test_qr_code_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/qr-code');

        $response->assertStatus(200);
        $response->assertSee('Content');
        $response->assertSee('Quick Templates');
        $response->assertSee('Download PNG');
        $response->assertSee('Error Correction');

    public function test_html_entity_tool_page_loads(): void
    {
        $response = $this->get('/tools/html-entity');

        $response->assertStatus(200);
        $response->assertSee('HTML Entity Encoder/Decoder');
        $response->assertSee('Encode special characters to HTML entities or decode them back to text');
    }

    public function test_html_entity_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/html-entity');

        $response->assertStatus(200);
        $response->assertSee('Encode');
        $response->assertSee('Decode');
        $response->assertSee('Common HTML Entities');
        $response->assertSee('Entity Reference');
    public function test_text_case_tool_page_loads(): void
    {
        $response = $this->get('/tools/text-case');

        $response->assertStatus(200);
        $response->assertSee('Text Case Converter');
        $response->assertSee('Convert text between different case formats');
    }

    public function test_text_case_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/text-case');

        $response->assertStatus(200);
        $response->assertSee('Input Text');
        $response->assertSee('Convert To');
        $response->assertSee('camelCase');
        $response->assertSee('snake_case');
    public function test_password_tool_page_loads(): void
    {
        $response = $this->get('/tools/password');

        $response->assertStatus(200);
        $response->assertSee('Password Generator');
        $response->assertSee('Generate secure random passwords');
    }

    public function test_password_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/password');

        $response->assertStatus(200);
        $response->assertSee('Generated Password');
        $response->assertSee('Strength');
        $response->assertSee('Options');
        $response->assertSee('Quick Presets');
    public function test_lorem_tool_page_loads(): void
    {
        $response = $this->get('/tools/lorem');

        $response->assertStatus(200);
        $response->assertSee('Lorem Ipsum Generator');
        $response->assertSee('Generate placeholder text');
    }

    public function test_lorem_tool_has_required_elements(): void
    {
        $response = $this->get('/tools/lorem');

        $response->assertStatus(200);
        $response->assertSee('Paragraphs');
        $response->assertSee('Sentences');
        $response->assertSee('Words');
        $response->assertSee('Generate');
    }

    public function test_all_pages_have_navigation(): void
    {
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/base-converter', '/tools/slug-generator', '/tools/color-picker', '/tools/qr-code', '/tools/html-entity'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/text-case'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/password'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/lorem'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            // All pages should have the nav with Dev Tools branding
            $response->assertSee('Dev Tools');
        }
    }

    public function test_all_pages_have_theme_toggle(): void
    {
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/base-converter', '/tools/slug-generator', '/tools/color-picker', '/tools/qr-code', '/tools/html-entity'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/text-case'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/regex', '/tools/password'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/lorem'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            // Theme toggle uses darkMode Alpine.js variable
            $response->assertSee('darkMode', false);
        }
    }

    public function test_all_pages_load_vite_assets(): void
    {
        // Code editor uses standalone template without Vite
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/base-converter', '/tools/slug-generator', '/tools/color-picker', '/tools/qr-code', '/tools/html-entity'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/text-case'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/password'];
        $pages = ['/', '/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/lorem'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            // Vite assets are loaded via @vite directive (with hash in filename)
            $response->assertSee('assets/app-', false);
        }
    }

    public function test_all_tool_pages_have_back_link(): void
    {
        // Code editor uses standalone template with home link instead of back
        $toolPages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/base-converter', '/tools/slug-generator', '/tools/color-picker', '/tools/qr-code', '/tools/html-entity'];
        $toolPages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/text-case'];
        $toolPages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/regex', '/tools/password'];
        $toolPages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/lorem'];

        foreach ($toolPages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('Back');
        }
    }

    public function test_nonexistent_route_returns_404(): void
    {
        $response = $this->get('/nonexistent-page');
        $response->assertStatus(404);
    }

    public function test_nonexistent_tool_returns_404(): void
    {
        $response = $this->get('/tools/nonexistent');
        $response->assertStatus(404);
    }

    public function test_health_check_endpoint(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }

    public function test_api_routes_reject_get_requests(): void
    {
        $apiRoutes = [
            '/api/v1/csv/convert',
            '/api/v1/yaml/convert',
            '/api/v1/markdown/convert',
            '/api/v1/sql/format',
            '/api/v1/base64/encode',
            '/api/v1/base64/decode',
            '/api/v1/uuid/generate',
            '/api/v1/uuid/validate',
            '/api/v1/hash/generate',
            '/api/v1/hash/verify',
            '/api/v1/url/encode',
            '/api/v1/url/decode',
            '/api/v1/url/parse',
        ];

        foreach ($apiRoutes as $route) {
            $response = $this->getJson($route);
            $response->assertStatus(405); // Method Not Allowed
        }
    }

    public function test_pages_have_csrf_token(): void
    {
        $pages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/base-converter', '/tools/slug-generator', '/tools/color-picker', '/tools/qr-code', '/tools/html-entity'];
        $pages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/text-case'];
        $pages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/password'];
        $pages = ['/tools/csv', '/tools/yaml', '/tools/markdown', '/tools/sql', '/tools/base64', '/tools/uuid', '/tools/hash', '/tools/url', '/tools/code-editor', '/tools/lorem'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('csrf-token', false);
        }
    }
}
