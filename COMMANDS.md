# MedicStore - Quick Terminal Commands

## Project Directory
```powershell
cd c:\laragon\www\MedicStore
```

## Installation & Initial Setup (Run Once)
```powershell
# Install Composer dependencies
composer install

# Install NPM dependencies
npm install

# Copy environment file
copy .env.example .env

# Generate app key
php artisan key:generate

# Create database in MySQL first:
# CREATE DATABASE MedicStore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Create storage symlink for file uploads
php artisan storage:link

# Compile Tailwind & frontend assets
npm run build
```

## Development Server (Daily Use)
```powershell
# Option 1: Laravel built-in server (simple)
php artisan serve
# Access at: http://localhost:8000

# Option 2: Vite dev server (hot reload, recommended for frontend development)
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

## Database Operations
```powershell
# Run all migrations
php artisan migrate

# Fresh migration (WARNING: Deletes all data!)
php artisan migrate:fresh

# Fresh migration + seed sample data
php artisan migrate:fresh --seed

# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset
```

## Cache & Config Management
```powershell
# Clear all caches
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Clear application cache (artisan)
php artisan optimize:clear
```

## Tinker Shell (Debug/Test Code)
```powershell
php artisan tinker

# Examples inside tinker:
App\Models\User::all();
App\Models\Medicine::count();
$user = App\Models\User::find(1);
$user->orders->count();
```

## Generate New Files
```powershell
# Create model + migration
php artisan make:model ModelName -m

# Create only migration
php artisan make:migration create_table_name

# Create controller
php artisan make:controller ControllerName

# Create middleware
php artisan make:middleware MiddlewareName

# Create seeder
php artisan make:seeder SeederName

# Create form request validation
php artisan make:request StoreUserRequest
```

## Frontend Development
```powershell
# Build for development (watch mode)
npm run dev

# Build for production (minified)
npm run build

# Run tests
npm run test
```

## Testing
```powershell
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with verbose output
php artisan test --verbose
```

## Storage & File Management
```powershell
# Create storage symlink (for public file access)
php artisan storage:link

# Remove storage symlink
rmdir storage/app/public
php artisan storage:link
```

## Useful Artisan Commands
```powershell
# List all routes
php artisan route:list

# List all available commands
php artisan list

# Show application info
php artisan about

# Generate model:show details
php artisan model:show App\Models\User
```

## Login Credentials (After Seeding)

### Admin
- Email: `admin@medicstore.com`
- Password: `password`

### Pharmacist
- Email: `pharmacist@medicstore.com`
- Password: `password`

### Patient 1
- Email: `john@example.com`
- Password: `password`

### Patient 2
- Email: `jane@example.com`
- Password: `password`

## Troubleshooting Commands
```powershell
# Check current environment
php artisan env

# Verify database connection
php artisan migrate --dry-run

# Show current routes with details
php artisan route:list --verbose

# Check Laravel logs (last entries)
Get-Content storage\logs\laravel.log -Tail 50

# Regenerate autoloader
composer dump-autoload
```

## Production Deployment (When Ready)
```powershell
# Install with no dev dependencies
composer install --no-dev

# Generate optimized config
php artisan config:cache

# Optimize autoloader
composer install --optimize-autoloader

# Migrate on production server
php artisan migrate --force

# Build frontend
npm run build
```

---

## File Upload Locations

After seeding and using the application:
- **Medicine Images**: `storage/app/public/medicines/`
- **Prescription Files**: `storage/app/public/prescriptions/`

Access via browser:
- `http://localhost:8000/storage/medicines/{filename}`
- `http://localhost:8000/storage/prescriptions/{filename}`

---

## Notes
- Press `Ctrl+C` to stop the development server
- NPM dev server requires separate terminal window
- Always run migrations before accessing database tables
- Default password for all test users is `password`
- Change passwords in production!
