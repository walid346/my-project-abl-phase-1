# The GOAT Tech Blog

A modern Laravel blog application built with Laravel 12, featuring a clean admin interface and public blog functionality.

## Features

- 📝 Article management with categories and tags
- 👨‍💼 Admin dashboard with authentication
- 🎨 Modern responsive design with Tailwind CSS
- 🌙 Dark/Light mode toggle
- 🔍 Article filtering by categories and tags
- 📱 Mobile-friendly interface
- 🖼️ Image handling with Intervention Image

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL or SQLite database

## Installation

### 1. Clone the repository

```bash
git clone <your-repository-url>
cd your-project-name
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Environment setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure your database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

For SQLite (simpler setup):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 6. Run migrations and seeders

```bash
# Create database tables
php artisan migrate

# Seed with sample data (optional)
php artisan db:seed
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the development server

```bash
# Start Laravel server, queue worker, and Vite dev server
composer run dev

# Or manually:
php artisan serve
```

Visit `http://localhost:8000` to see your blog!

## Usage

### Public Interface

- **Homepage**: Browse all articles with category filtering
- **Article View**: Read full articles with tags
- **Category Filtering**: Click on categories to filter articles
- **Tag Filtering**: Click on tags to see related articles
- **About Page**: Learn more about the blog

### Admin Interface

Access the admin panel at `/admin` (you'll need to create an admin user first).

#### Creating an Admin User

```bash
php artisan tinker
```

Then in the tinker console:
```php
App\Models\Admin::create([
    'name' => 'Your Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('your-password')
]);
```

#### Admin Features

- **Dashboard**: Overview of articles, categories, and tags
- **Article Management**: Create, edit, delete articles
- **Category Management**: Organize articles by categories
- **Tag Management**: Add tags to articles
- **Image Upload**: Upload and manage article images

### Development Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run tests
composer test

# Code formatting
./vendor/bin/pint

# Watch for file changes during development
npm run dev
```

## Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php      # Admin dashboard
│   │   ├── PublicController.php     # Public blog pages
│   │   └── CategoryController.php   # Category management
│   └── Models/
│       ├── Article.php              # Article model
│       ├── Category.php             # Category model
│       ├── Tag.php                  # Tag model
│       └── Admin.php                # Admin user model
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── public.blade.php     # Public layout
│       │   └── admin.blade.php      # Admin layout
│       ├── public/                  # Public blog views
│       └── admin/                   # Admin panel views
└── routes/
    └── web.php                      # Application routes
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

If you encounter any issues or have questions, please open an issue on GitHub.