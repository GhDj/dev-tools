<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ToolController extends Controller
{
    public function index(): View
    {
        $tools = [
            [
                'name' => 'CSV Converter',
                'description' => 'Convert CSV to JSON, SQL INSERT statements, or PHP arrays',
                'route' => 'tools.csv',
                'icon' => 'table',
            ],
            [
                'name' => 'YAML/JSON Converter',
                'description' => 'Convert between YAML and JSON formats bidirectionally',
                'route' => 'tools.yaml',
                'icon' => 'code',
            ],
            [
                'name' => 'JSON Parser',
                'description' => 'Format, minify, validate, and repair JSON data',
                'route' => 'tools.json',
                'icon' => 'json',
            ],
            [
                'name' => 'Markdown Preview',
                'description' => 'Preview Markdown as HTML with export option',
                'route' => 'tools.markdown',
                'icon' => 'document',
            ],
            [
                'name' => 'SQL Formatter',
                'description' => 'Format, beautify, or compress SQL queries',
                'route' => 'tools.sql',
                'icon' => 'database',
            ],
            [
                'name' => 'Base64 Encoder',
                'description' => 'Encode/decode text and files to/from Base64',
                'route' => 'tools.base64',
                'icon' => 'lock',
            ],
            [
                'name' => 'UUID Generator',
                'description' => 'Generate, validate, and format UUIDs (v4)',
                'route' => 'tools.uuid',
                'icon' => 'fingerprint',
            ],
            [
                'name' => 'Hash Generator',
                'description' => 'Generate MD5, SHA-1, SHA-256, SHA-512 hashes',
                'route' => 'tools.hash',
                'icon' => 'hash',
            ],
            [
                'name' => 'URL Encoder',
                'description' => 'Encode/decode URLs and parse URL components',
                'route' => 'tools.url',
                'icon' => 'link',
            ],
            [
                'name' => 'Code Editor',
                'description' => 'Write HTML, CSS, JS with live preview',
                'route' => 'tools.code-editor',
                'icon' => 'editor',
            ],
            [
                'name' => 'Regex Tester',
                'description' => 'Test and debug regular expressions with live matching',
                'route' => 'tools.regex',
                'icon' => 'regex',
            ],
            [
                'name' => 'Base Converter',
                'description' => 'Convert between binary, octal, decimal, and hex',
                'route' => 'tools.base-converter',
                'icon' => 'calculator',
            ],
            [
                'name' => 'Slug Generator',
                'description' => 'Convert text to URL-friendly slugs',
                'route' => 'tools.slug-generator',
                'icon' => 'slug',
            ],
            [
                'name' => 'Color Picker',
                'description' => 'Pick colors and convert between HEX, RGB, HSL, CMYK',
                'route' => 'tools.color-picker',
                'icon' => 'color',
            ],
            [
                'name' => 'QR Code Generator',
                'description' => 'Generate QR codes for URLs, text, and more',
                'route' => 'tools.qr-code',
                'icon' => 'qrcode',
            ],
            [
                'name' => 'HTML Entity Encoder',
                'description' => 'Encode/decode HTML entities and special characters',
                'route' => 'tools.html-entity',
                'icon' => 'html-entity',
            ],
            [
                'name' => 'Text Case Converter',
                'description' => 'Convert text to camelCase, snake_case, and more',
                'route' => 'tools.text-case',
                'icon' => 'text-case',
            ],
            [
                'name' => 'Password Generator',
                'description' => 'Generate secure random passwords',
                'route' => 'tools.password',
                'icon' => 'key',
            ],
            [
                'name' => 'Lorem Ipsum Generator',
                'description' => 'Generate placeholder text for designs',
                'route' => 'tools.lorem',
                'icon' => 'text',
            ],
            [
                'name' => 'Cron Parser',
                'description' => 'Parse and explain cron expressions',
                'route' => 'tools.cron',
                'icon' => 'clock',
            ],
            [
                'name' => 'JWT Decoder',
                'description' => 'Decode and inspect JSON Web Tokens',
                'route' => 'tools.jwt',
                'icon' => 'jwt',
            ],
            [
                'name' => 'Timestamp Converter',
                'description' => 'Convert Unix timestamps to dates and vice versa',
                'route' => 'tools.timestamp',
                'icon' => 'timestamp',
            ],
        ];

        return view('home', compact('tools'));
    }

    public function csv(): View
    {
        return view('tools.csv');
    }

    public function yaml(): View
    {
        return view('tools.yaml');
    }

    public function json(): View
    {
        return view('tools.json');
    }

    public function markdown(): View
    {
        return view('tools.markdown');
    }

    public function sql(): View
    {
        return view('tools.sql');
    }

    public function base64(): View
    {
        return view('tools.base64');
    }

    public function uuid(): View
    {
        return view('tools.uuid');
    }

    public function hash(): View
    {
        return view('tools.hash');
    }

    public function url(): View
    {
        return view('tools.url');
    }

    public function codeEditor(): View
    {
        return view('tools.code-editor');
    }

    public function regex(): View
    {
        return view('tools.regex');
    }

    public function baseConverter(): View
    {
        return view('tools.base-converter');
    }

    public function slugGenerator(): View
    {
        return view('tools.slug-generator');
    }

    public function colorPicker(): View
    {
        return view('tools.color-picker');
    }

    public function qrCode(): View
    {
        return view('tools.qr-code');
    }

    public function htmlEntity(): View
    {
        return view('tools.html-entity');
    }

    public function textCase(): View
    {
        return view('tools.text-case');
    }

    public function password(): View
    {
        return view('tools.password-generator');
    }

    public function lorem(): View
    {
        return view('tools.lorem-ipsum');
    }

    public function cron(): View
    {
        return view('tools.cron-parser');
    }

    public function jwt(): View
    {
        return view('tools.jwt');
    }

    public function timestamp(): View
    {
        return view('tools.timestamp');
    }
}
