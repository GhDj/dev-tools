<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UrlEncoderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function __construct(
        private UrlEncoderService $urlEncoderService
    ) {}

    public function encode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
            'mode' => 'sometimes|string|in:component,full,query',
        ]);

        $result = $this->urlEncoderService->encode(
            $validated['input'],
            $validated['mode'] ?? 'component'
        );

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function decode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
        ]);

        $result = $this->urlEncoderService->decode($validated['input']);

        return response()->json($result);
    }

    public function parse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => 'required|string',
        ]);

        $result = $this->urlEncoderService->parseUrl($validated['url']);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }
}
