<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JsonFormatterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function __construct(
        private JsonFormatterService $jsonService
    ) {}

    public function format(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'json' => 'required|string',
            'mode' => 'nullable|in:format,minify,validate,repair,sort',
            'indent' => 'nullable|integer|min:1|max:8',
        ]);

        $json = $validated['json'];
        $mode = $validated['mode'] ?? 'format';
        $indent = $validated['indent'] ?? 4;

        try {
            $result = match ($mode) {
                'format' => $this->jsonService->format($json, $indent),
                'minify' => $this->jsonService->minify($json),
                'validate' => $this->jsonService->validate($json),
                'repair' => $this->jsonService->repair($json),
                'sort' => $this->jsonService->sortKeys($json),
            };

            // For validate mode, return the validation result directly
            if ($mode === 'validate') {
                return response()->json([
                    'success' => true,
                    'validation' => $result,
                ]);
            }

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
