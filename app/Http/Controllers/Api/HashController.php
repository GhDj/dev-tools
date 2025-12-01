<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HashService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HashController extends Controller
{
    public function __construct(
        private HashService $hashService
    ) {}

    public function hash(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
            'algorithm' => 'sometimes|string',
        ]);

        if (isset($validated['algorithm'])) {
            $result = $this->hashService->hash($validated['input'], $validated['algorithm']);
        } else {
            $result = $this->hashService->hashAll($validated['input']);
        }

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
            'hash' => 'required|string',
            'algorithm' => 'sometimes|string',
        ]);

        $result = $this->hashService->verify(
            $validated['input'],
            $validated['hash'],
            $validated['algorithm'] ?? null
        );

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result);
    }
}
