<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProductsJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function import(Request $request): JsonResponse
    {
        try {
            $products = $request->input("products", []);
            
            if (empty($products)) {
                return response()->json([
                    "success" => false,
                    "message" => "No products data provided"
                ], 400);
            }

            // Dispatch the job to the queue
            ImportProductsJob::dispatch($products);

            Log::info("Product import job dispatched for " . count($products) . " products");

            return response()->json([
                "success" => true,
                "message" => "Import job dispatched successfully",
                "products_count" => count($products)
            ], 202);

        } catch (\Exception $e) {
            Log::error("Import failed: " . $e->getMessage());
            
            return response()->json([
                "success" => false,
                "message" => "Import failed: " . $e->getMessage()
            ], 500);
        }
    }
}
