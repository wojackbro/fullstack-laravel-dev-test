<?php

require_once "vendor/autoload.php";

$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$products = json_decode(file_get_contents("products.json"), true);

$response = \Illuminate\Support\Facades\Http::post("http://localhost:8000/api/import", [
    "products" => $products
]);

echo "Response: " . $response->body() . "
";
