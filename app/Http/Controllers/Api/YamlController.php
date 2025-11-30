<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\YamlConverterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class YamlController extends Controller
{
    public function __construct(
        private YamlConverterService $yamlService
    ) {}

    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
            'direction' => 'required|in:yaml-to-json,json-to-yaml',
            'indent' => 'nullable|integer|min:1|max:8',
        ]);

        $input = $validated['input'];
        $direction = $validated['direction'];
        $indent = $validated['indent'] ?? 2;

        $result = match ($direction) {
            'yaml-to-json' => $this->yamlService->yamlToJson($input),
            'json-to-yaml' => $this->yamlService->jsonToYaml($input, $indent),
        };

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'result' => $result['result'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 422);
    }
}
