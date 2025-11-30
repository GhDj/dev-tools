<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CsvConverterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function __construct(
        private CsvConverterService $csvService
    ) {}

    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'csv' => 'required|string',
            'format' => 'required|in:json,sql,php',
            'delimiter' => 'nullable|string|max:1',
            'has_headers' => 'nullable|boolean',
            'table_name' => 'nullable|string|max:100',
        ]);

        $csv = $validated['csv'];
        $format = $validated['format'];
        $delimiter = $validated['delimiter'] ?? ',';
        $hasHeaders = $validated['has_headers'] ?? true;
        $tableName = $validated['table_name'] ?? 'table_name';

        try {
            $result = match ($format) {
                'json' => $this->csvService->toJson($csv, $delimiter, $hasHeaders),
                'sql' => $this->csvService->toSql($csv, $tableName, $delimiter, $hasHeaders),
                'php' => $this->csvService->toPhpArray($csv, $delimiter, $hasHeaders),
            };

            return response()->json([
                'success' => true,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Conversion failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
