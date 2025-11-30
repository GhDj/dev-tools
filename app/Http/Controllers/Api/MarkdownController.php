<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MarkdownConverterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarkdownController extends Controller
{
    public function __construct(
        private MarkdownConverterService $markdownService
    ) {}

    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'markdown' => 'required|string',
            'full_page' => 'nullable|boolean',
            'title' => 'nullable|string|max:200',
        ]);

        $markdown = $validated['markdown'];
        $fullPage = $validated['full_page'] ?? false;
        $title = $validated['title'] ?? 'Markdown Preview';

        try {
            $html = $fullPage
                ? $this->markdownService->toFullHtml($markdown, $title)
                : $this->markdownService->toHtml($markdown);

            return response()->json([
                'success' => true,
                'result' => $html,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Conversion failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
