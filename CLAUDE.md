# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 11 application using the Inertia.js stack with Vue 3 and Tailwind CSS. It includes Laravel Jetstream for authentication scaffolding with Sanctum for API authentication.

**Tech Stack:**
- Laravel 11.46.1 (PHP 8.2+)
- Vue 3 with Inertia.js (SPA-like experience without building an API)
- Tailwind CSS for styling
- Vite for asset bundling
- SQLite database (default)
- Laravel Jetstream with Inertia stack
- Laravel Sanctum for API authentication
- Laravel Fortify for authentication backend

## Development Commands

### Setup
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file and generate application key
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Link storage directory (if needed)
php artisan storage:link
```

### Running the Application
```bash
# Start the development server (backend)
php artisan serve

# Start Vite dev server (frontend assets) - run in separate terminal
npm run dev

# Build assets for production
npm run build
```

### Database
```bash
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations and re-run them
php artisan migrate:fresh

# Run migrations with seeders
php artisan migrate --seed

# Create new migration
php artisan make:migration create_table_name
```

### Testing
```bash
# Run all tests
php artisan test

# Or using PHPUnit directly
vendor/bin/phpunit

# Run specific test file
php artisan test --filter=TestClassName

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format code using Laravel Pint
vendor/bin/pint

# Check code formatting without making changes
vendor/bin/pint --test
```

### Artisan Commands
```bash
# List all available routes
php artisan route:list

# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Create a new controller
php artisan make:controller ControllerName

# Create a new model with migration
php artisan make:model ModelName -m

# Create a complete resource (model, migration, controller, etc.)
php artisan make:model ModelName -mcr

# Enter Tinker REPL
php artisan tinker
```

## Architecture

### Frontend (Inertia.js + Vue)

**Key Concept:** Inertia.js allows building SPAs using server-side routing and controllers. No need for a separate API layer.

- **Entry point:** `resources/js/app.js` - Sets up Vue 3, Inertia, and Ziggy for route helpers
- **Pages:** `resources/js/Pages/` - Vue components rendered by Inertia (each corresponds to a controller response)
- **Components:** `resources/js/Components/` - Reusable Vue components
- **Layouts:** `resources/js/Layouts/AppLayout.vue` - Main application layout

**How Inertia Works:**
- Controllers return `Inertia::render('PageName', ['data' => $data])` instead of views
- Inertia loads the corresponding Vue component from `resources/js/Pages/PageName.vue`
- Props passed from controller are available in the Vue component
- Links use `<Link>` component instead of `<a>` for SPA-like navigation
- Forms submit using Inertia's `useForm()` composable

### Backend (Laravel)

- **Routes:** `routes/web.php` - Web routes (Jetstream adds auth routes automatically)
- **Controllers:** `app/Http/Controllers/` - Request handlers
- **Models:** `app/Models/` - Eloquent ORM models
- **Middleware:** `app/Http/Middleware/HandleInertiaRequests.php` - Shares data with all Inertia responses
- **Actions:** `app/Actions/` - Jetstream actions for user management (Fortify pattern)

### Authentication & User Management

Laravel Jetstream provides comprehensive authentication features:

- **Fortify:** Handles authentication backend (registration, login, password reset, email verification, 2FA)
- **Sanctum:** API token authentication
- **Actions pattern:** User registration, profile updates, password changes are in `app/Actions/Fortify/`
- **Configured features** (in `config/jetstream.php`):
  - Account deletion (enabled)
  - Profile photos, API tokens, teams (currently disabled)

### Database

- **Default:** SQLite (`database/database.sqlite`)
- **Migrations:** `database/migrations/` - Database schema definitions
- **Seeders:** `database/seeders/` - Test data generators
- **Factories:** `database/factories/` - Model factories for testing

### Asset Pipeline (Vite)

- **Config:** `vite.config.js`
- **Input:** `resources/js/app.js` (JavaScript/Vue) and `resources/css/app.css` (Tailwind)
- **Output:** Compiled to `public/build/`
- **HMR:** Hot module replacement enabled in development

## Important Patterns

### Creating New Pages

1. Add route in `routes/web.php`:
   ```php
   Route::get('/example', function () {
       return Inertia::render('Example', ['data' => 'value']);
   })->name('example');
   ```

2. Create Vue component at `resources/js/Pages/Example.vue`:
   ```vue
   <script setup>
   defineProps({ data: String })
   </script>
   <template>
     <AppLayout title="Example">
       {{ data }}
     </AppLayout>
   </template>
   ```

### Working with Forms in Inertia

Use Inertia's `useForm()` composable for handling form submissions:
```javascript
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
})

form.post('/endpoint')
```

### Sharing Data Globally

Edit `app/Http/Middleware/HandleInertiaRequests.php` to share data with all Inertia responses.

### Using Ziggy for Routes

Ziggy provides Laravel route helpers in JavaScript:
```javascript
import { route } from 'ziggy-js'

// Use in templates
route('dashboard')
// Or with parameters
route('user.show', { id: 1 })
```

## Testing

Test structure follows Laravel conventions:
- **Feature tests:** `tests/Feature/` - Test complete features/HTTP requests
- **Unit tests:** `tests/Unit/` - Test individual classes/methods
- **Base TestCase:** `tests/TestCase.php` - Extended by all tests

Jetstream includes comprehensive feature tests for all authentication flows.
- continue