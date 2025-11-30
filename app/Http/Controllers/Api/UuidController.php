<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UuidService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UuidController extends Controller
{
    public function __construct(
        private UuidService $uuidService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'count' => 'sometimes|integer|min:1|max:100',
            'format' => 'sometimes|string|in:lowercase,uppercase,no-hyphens,braces,urn',
        ]);

        $count = $validated['count'] ?? 1;
        $format = $validated['format'] ?? 'lowercase';

        try {
            if ($count === 1) {
                $uuid = $this->uuidService->generateV4();
                if ($format !== 'lowercase') {
                    $uuid = $this->uuidService->format($uuid, $format);
                }

                return response()->json([
                    'success' => true,
                    'uuid' => $uuid,
                ]);
            }

            $uuids = $this->uuidService->generateBulk($count);
            if ($format !== 'lowercase') {
                $uuids = array_map(fn($uuid) => $this->uuidService->format($uuid, $format), $uuids);
            }

            return response()->json([
                'success' => true,
                'uuids' => $uuids,
                'count' => count($uuids),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Generation failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function validate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'uuid' => 'required|string',
        ]);

        $result = $this->uuidService->validate($validated['uuid']);

        return response()->json($result);
    }
}
