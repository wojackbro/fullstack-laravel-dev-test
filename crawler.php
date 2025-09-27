<?php

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ProductCrawler
{
    private $client;
    private $baseUrl = 'https://sandbox.oxylabs.io/products';
    private $products = [];

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);
    }

    public function crawl()
    {
        try {
            echo "Starting to crawl products from: {$this->baseUrl}\n";
            
            $response = $this->client->get($this->baseUrl);
            $html = $response->getBody()->getContents();
            
            $crawler = new Crawler($html);
            
            // Extract products from the page
            $crawler->filter('.product-item, .product, [class*="product"]')->each(function ($node) {
                $this->extractProduct($node);
            });
            
            // If no products found with common selectors, try alternative selectors
            if (empty($this->products)) {
                $crawler->filter('div[class*="item"], .card, [data-testid*="product"]')->each(function ($node) {
                    $this->extractProduct($node);
                });
            }
            
            echo "Found " . count($this->products) . " products\n";
            
            return $this->products;
            
        } catch (Exception $e) {
            echo "Error crawling products: " . $e->getMessage() . "\n";
            return [];
        }
    }

    private function extractProduct($node)
    {
        try {
            $product = [];
            
            // Extract title
            $titleNode = $node->filter('h1, h2, h3, h4, h5, h6, .title, .product-title, [class*="title"]')->first();
            if ($titleNode->count() > 0) {
                $product['title'] = trim($titleNode->text());
            }
            
            // Extract price
            $priceNode = $node->filter('.price, .cost, [class*="price"], [class*="cost"]')->first();
            if ($priceNode->count() > 0) {
                $priceText = $priceNode->text();
                // Extract numeric value from price
                preg_match('/[\d,]+\.?\d*/', $priceText, $matches);
                $product['price'] = isset($matches[0]) ? floatval(str_replace(',', '', $matches[0])) : 0;
            }
            
            // Extract image URL
            $imageNode = $node->filter('img')->first();
            if ($imageNode->count() > 0) {
                $imageUrl = $imageNode->attr('src');
                if ($imageUrl) {
                    // Convert relative URLs to absolute
                    if (strpos($imageUrl, 'http') !== 0) {
                        $imageUrl = $this->baseUrl . '/' . ltrim($imageUrl, '/');
                    }
                    $product['image_url'] = $imageUrl;
                }
            }
            
            // Extract description
            $descNode = $node->filter('.description, .desc, .product-desc, [class*="description"]')->first();
            if ($descNode->count() > 0) {
                $product['description'] = trim($descNode->text());
            }
            
            // Extract category
            $categoryNode = $node->filter('.category, .cat, [class*="category"]')->first();
            if ($categoryNode->count() > 0) {
                $product['category'] = trim($categoryNode->text());
            }
            
            // Only add product if it has at least a title
            if (!empty($product['title'])) {
                $product['crawled_at'] = date('Y-m-d H:i:s');
                $this->products[] = $product;
            }
            
        } catch (Exception $e) {
            echo "Error extracting product: " . $e->getMessage() . "\n";
        }
    }

    public function exportToJson($filename = 'products.json')
    {
        $json = json_encode($this->products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($filename, $json);
        echo "Products exported to: {$filename}\n";
        return $filename;
    }
}

// Run the crawler
$crawler = new ProductCrawler();
$products = $crawler->crawl();

if (!empty($products)) {
    $crawler->exportToJson('products.json');
    echo "Crawling completed successfully!\n";
} else {
    echo "No products found. The website structure might have changed.\n";
    echo "You may need to inspect the HTML and update the selectors.\n";
}
