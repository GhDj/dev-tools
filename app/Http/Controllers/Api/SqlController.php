<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SqlFormatterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SqlController extends Controller
{
    public function __construct(
        private SqlFormatterService $sqlService
    ) {}

    public function format(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sql' => 'required|string',
            'mode' => 'nullable|in:format,compress,highlight',
        ]);

        $sql = $validated['sql'];
        $mode = $validated['mode'] ?? 'format';

        try {
            $result = match ($mode) {
                'format' => $this->sqlService->format($sql),
                'compress' => $this->sqlService->compress($sql),
                'highlight' => $this->sqlService->highlight($sql),
            };

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Formatting failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
