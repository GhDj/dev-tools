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
}
