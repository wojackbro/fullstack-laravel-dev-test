<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function handle()
    {
        Log::info("Starting import of " . count($this->products) . " products");

        foreach ($this->products as $productData) {
            try {
                // Create or update product
                $product = Product::updateOrCreate(
                    ["title" => $productData["title"]],
                    [
                        "price" => $productData["price"] ?? 0,
                        "description" => $productData["description"] ?? null,
                        "category" => $productData["category"] ?? null,
                        "crawled_at" => $productData["crawled_at"] ?? now()
                    ]
                );

                // Create image if URL exists
                if (!empty($productData["image_url"])) {
                    Image::updateOrCreate(
                        [
                            "product_id" => $product->id,
                            "image_url" => $productData["image_url"]
                        ],
                        [
                            "alt_text" => $productData["title"],
                            "sort_order" => 0
                        ]
                    );
                }

                Log::info("Imported product: " . $product->title);
            } catch (\Exception $e) {
                Log::error("Failed to import product: " . $e->getMessage());
            }
        }

        Log::info("Product import completed");
    }
}
