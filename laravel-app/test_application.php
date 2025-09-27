<?php

echo "🧪 Testing Laravel Full Stack Application\n";
echo "=====================================\n\n";

// Test 1: Check if Laravel app is working
echo "1. Testing Laravel Application...\n";
try {
    require_once "vendor/autoload.php";
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "   ✅ Laravel application loaded successfully\n";
} catch (Exception $e) {
    echo "   ❌ Laravel application failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check database connection
echo "\n2. Testing Database Connection...\n";
try {
    $productCount = App\Models\Product::count();
    $imageCount = App\Models\Image::count();
    echo "   ✅ Database connected successfully\n";
    echo "   📊 Products in database: $productCount\n";
    echo "   📊 Images in database: $imageCount\n";
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
}

// Test 3: Check if products have images
echo "\n3. Testing Product-Image Relationships...\n";
try {
    $productsWithImages = App\Models\Product::whereHas('images')->count();
    echo "   ✅ Product-Image relationships working\n";
    echo "   📊 Products with images: $productsWithImages\n";
} catch (Exception $e) {
    echo "   ❌ Product-Image relationships failed: " . $e->getMessage() . "\n";
}

// Test 4: Check sample product data
echo "\n4. Testing Sample Product Data...\n";
try {
    $sampleProduct = App\Models\Product::with('images')->first();
    if ($sampleProduct) {
        echo "   ✅ Sample product found\n";
        echo "   📝 Title: " . $sampleProduct->title . "\n";
        echo "   💰 Price: $" . number_format($sampleProduct->price, 2) . "\n";
        echo "   🏷️  Category: " . ($sampleProduct->category ?? 'N/A') . "\n";
        echo "   🖼️  Images: " . $sampleProduct->images->count() . "\n";
    } else {
        echo "   ⚠️  No products found in database\n";
    }
} catch (Exception $e) {
    echo "   ❌ Sample product test failed: " . $e->getMessage() . "\n";
}

echo "\n🎉 Application test completed!\n";
echo "\n📋 Next Steps:\n";
echo "   1. Start the server: php artisan serve\n";
echo "   2. Visit frontend: http://localhost:8000/view/products\n";
echo "   3. Visit admin: http://localhost:8000/admin\n";
echo "   4. Login with admin credentials\n";
