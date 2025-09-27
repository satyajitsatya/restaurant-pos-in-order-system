Part 16: Final Setup Commands
Step 16.1: Complete Installation Script
Create a file called install.sh in your project root:

bash
#!/bin/bash

echo "🚀 Setting up Restaurant POS System..."

# Install PHP dependencies

echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies

echo "📦 Installing Node.js dependencies..."
npm install

# Generate application key

echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link

echo "🔗 Creating storage link..."
php artisan storage:link

# Run migrations

echo "🗄️ Running database migrations..."
php artisan migrate

# Seed the database

echo "🌱 Seeding database with sample data..."
php artisan db:seed

# Build frontend assets

echo "🏗️ Building frontend assets..."
npm run build

# Set permissions (for Linux/Mac)

echo "🔐 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Installation completed!"
echo "👤 Admin Login: admin@restaurant.com / password123"
echo "🍳 Kitchen Login: kitchen@restaurant.com / password123"
echo "🏃‍♂️ Run: php artisan serve"
