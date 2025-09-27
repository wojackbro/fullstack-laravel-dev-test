# Laravel Full Stack Development Test

**Developer:** [Abid Hossain](https://www.abidhossain.me)

This project demonstrates a complete Laravel full-stack application with product crawling, asynchronous import, admin panel, and dynamic frontend.

## 🚀 Features

- **PHP Crawler**: Extracts product data from sandbox.oxylabs.io/products
- **Laravel Backend**: Asynchronous product import using queues
- **Filament Admin**: Complete admin panel for product management
- **Livewire Frontend**: Dynamic product listing with search and pagination
- **TailwindCSS**: Modern, responsive UI design
- **AlpineJS**: Interactive frontend components

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- Node.js (for asset compilation)
- MySQL/SQLite database

## 🛠️ Installation

### 1. Clone the repository
```bash
git clone https://github.com/wojackbro/fullstack-laravel-dev-test.git
cd fullstack-laravel-dev-test
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Or for MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_products
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Create admin user
```bash
php artisan make:filament-user
```

### 7. Start the development server
```bash
php artisan serve
```

## 🔧 Usage

### Running the Crawler
```bash
# From the root directory
php crawler.php
```

This will create a `products.json` file with crawled product data.

### Importing Products
```bash
# Import products using the API
curl -X POST http://localhost:8000/api/import \
  -H "Content-Type: application/json" \
  -d @products.json

# Or import directly using artisan
php artisan tinker --execute="
\$products = json_decode(file_get_contents('products.json'), true);
App\Jobs\ImportProductsJob::dispatch(\$products);
"
```

### Processing Queue Jobs
```bash
# Process jobs once
php artisan queue:work --once

# Or run queue worker continuously
php artisan queue:work
```

## 🌐 Access Points

- **Frontend**: http://localhost:8000/view/products
- **Admin Panel**: http://localhost:8000/admin
- **API Endpoint**: http://localhost:8000/api/import

## 📁 Project Structure

```
├── crawler.php                 # PHP crawler script
├── products.json              # Crawled product data
├── laravel-app/               # Laravel application
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   └── ImportController.php
│   │   ├── Jobs/
│   │   │   └── ImportProductsJob.php
│   │   ├── Livewire/
│   │   │   └── ProductList.php
│   │   └── Models/
│   │       ├── Product.php
│   │       └── Image.php
│   ├── Filament/Resources/Products/
│   │   └── ProductResource.php
│   └── database/migrations/
│       ├── create_products_table.php
│       └── create_images_table.php
└── resources/views/
    ├── layouts/app.blade.php
    └── livewire/product-list.blade.php
```

## 🎯 Technical Implementation

### 1. PHP Crawler
- Uses Guzzle HTTP client for web requests
- Symfony DomCrawler for HTML parsing
- Extracts: title, price, image_url, description, category
- Exports data as JSON

### 2. Laravel Backend
- **Models**: Product and Image with proper relationships
- **Migrations**: Database schema for products and images
- **Jobs**: Asynchronous import using Laravel queues
- **API**: RESTful endpoint for product import

### 3. Filament Admin
- **ProductResource**: Complete CRUD operations
- **Image Management**: Handle product images
- **Search & Filter**: Built-in table functionality
- **Responsive Design**: Mobile-friendly admin interface

### 4. Livewire Frontend
- **ProductList Component**: Dynamic product listing
- **Search**: Real-time search functionality
- **Pagination**: 25 products per page (configurable)
- **Sorting**: Sort by creation date, price, etc.
- **Responsive Grid**: Mobile-first design

### 5. Styling & UX
- **TailwindCSS**: Utility-first CSS framework
- **AlpineJS**: Lightweight JavaScript framework
- **Responsive Design**: Works on all device sizes
- **Modern UI**: Clean, professional appearance

## 🔄 Queue System

The application uses Laravel's queue system for asynchronous processing:

1. **Import Job**: `ImportProductsJob` handles product import
2. **Queue Driver**: Default database queue driver
3. **Processing**: Run `php artisan queue:work` to process jobs

## 📊 Database Schema

### Products Table
- `id` (Primary Key)
- `title` (String)
- `price` (Decimal 10,2)
- `description` (Text, Nullable)
- `category` (String, Nullable)
- `crawled_at` (Timestamp, Nullable)
- `created_at`, `updated_at` (Timestamps)

### Images Table
- `id` (Primary Key)
- `product_id` (Foreign Key)
- `image_url` (String)
- `alt_text` (String, Nullable)
- `sort_order` (Integer, Default: 0)
- `created_at`, `updated_at` (Timestamps)

## 🧪 Testing

### Test the Crawler
```bash
php crawler.php
# Check products.json for results
```

### Test the Import
```bash
php artisan tinker --execute="
\$products = json_decode(file_get_contents('products.json'), true);
App\Jobs\ImportProductsJob::dispatch(\$products);
"
php artisan queue:work --once
```

### Test the Frontend
1. Start the server: `php artisan serve`
2. Visit: http://localhost:8000/view/products
3. Test search, pagination, and sorting

### Test the Admin Panel
1. Visit: http://localhost:8000/admin
2. Login with admin credentials
3. Manage products and images

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Configure proper database credentials
3. Set up queue workers (Supervisor recommended)
4. Configure web server (Nginx/Apache)
5. Set up SSL certificates

### Queue Workers
For production, use Supervisor to manage queue workers:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

## 📝 API Documentation

### POST /api/import
Import products asynchronously.

**Request Body:**
```json
{
  "products": [
    {
      "title": "Product Name",
      "price": 99.99,
      "description": "Product description",
      "category": "Category Name",
      "image_url": "https://example.com/image.jpg",
      "crawled_at": "2025-09-27 14:00:00"
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Import job dispatched successfully",
  "products_count": 33
}
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request


If you encounter any issues or have questions, please:
1. Check the Laravel documentation
2. Review the Filament documentation
3. Check the Livewire documentation
4. Create an issue in the repository

---

**Note**: This project was created as part of a technical assessment and demonstrates various Laravel and modern web development concepts.

**Developer Contact:**
- **Name:** Abid Hossain
- **Website:** [www.abidhossain.me](https://www.abidhossain.me)
- **GitHub:** [@wojackbro](https://github.com/wojackbro)
