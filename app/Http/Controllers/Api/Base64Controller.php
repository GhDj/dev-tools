<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Base64Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Base64Controller extends Controller
{
    public function __construct(
        private Base64Service $base64Service
    ) {}

    public function encode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
        ]);

        try {
            $result = $this->base64Service->encode($validated['input']);

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Encoding failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function decode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => 'required|string',
        ]);

        $result = $this->base64Service->decode($validated['input']);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'result' => $result['result'],
                'is_binary' => $result['is_binary'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 422);
    }

    public function encodeFile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|max:5120', // 5MB max
        ]);

        $file = $request->file('file');

        try {
            $content = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();
            $result = $this->base64Service->encodeFile($content, $mimeType);

            return response()->json([
                'success' => true,
                'result' => $result,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'File encoding failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
