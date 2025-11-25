# Laravel Translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rdcstarr/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/rdcstarr/laravel-translations)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/rdcstarr/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rdcstarr/laravel-translations/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rdcstarr/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rdcstarr/laravel-translations/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rdcstarr/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/rdcstarr/laravel-translations)

A simple and elegant Laravel package for managing translations across multiple languages with database storage and caching.

## Requirements

This package requires:

-   **Laravel 10+**
-   **PHP 8.1+**
-   **rdcstarr/laravel-languages** - for language management

## Installation

You can install the package via composer:

```bash
composer require rdcstarr/laravel-translations
```

### Automatic Installation (Recommended)

Run the install command to publish and run the migrations:

```bash
php artisan translations:install
```

### Manual Installation

Alternatively, you can install manually:

1. Publish the migrations:

```bash
php artisan vendor:publish --provider="Rdcstarr\Translations\TranslationsServiceProvider" --tag="laravel-translations-migrations"
```

2. Run the migrations:

```bash
php artisan migrate
```

## Usage

### Using the Helper Function

The package provides a convenient `translations()` helper function:

```php
// Get a translation for the current language
translations('welcome'); // Returns the translation value

// Get a translation with a default value
translations('welcome', 'Welcome!');

// Get a translation for a specific language
translations('welcome', languageCode: 'ro'); // Returns Romanian translation
translations('greeting', 'Hello', 'de'); // Returns German translation with default

// Access the service directly
translations()->all(); // Get all translations for current language
translations()->has('welcome'); // Check if translation exists
translations()->set('new_key', 'New value'); // Set a translation
```

### Using the Facade

```php
use Rdcstarr\Translations\Facades\Translations;

// Get a translation
Translations::get('welcome');
Translations::get('greeting', 'Hello');

// Set a translation
Translations::set('welcome', 'Welcome!');
Translations::setMany([
    'hello' => 'Hello',
    'goodbye' => 'Goodbye',
]);

// Check if exists
Translations::has('welcome');

// Delete a translation
Translations::delete('old_key');

// Get all translations
Translations::all();
```

### Using Dependency Injection

```php
use Rdcstarr\Translations\TranslationsService;

class MyController extends Controller
{
    public function __construct(
        protected TranslationsService $translations
    ) {}

    public function index()
    {
        $welcome = $this->translations->get('welcome');

        // The service uses the current app locale by default
        // For a different language, create a new instance:
        $service = new TranslationsService('ro');
        $welcome = $service->get('welcome');
    }
}
```

### Artisan Commands

The package includes several artisan commands for managing translations:

#### List Translations

```bash
# List all translations for current language
php artisan translations:list

# List translations for a specific language
php artisan translations:list ro
```

#### Get Translation

```bash
# Get a translation for current language
php artisan translations:get welcome

# Get a translation for a specific language
php artisan translations:get welcome --language=ro
```

#### Set Translation

```bash
# Set a translation for current language
php artisan translations:set welcome "Welcome to our app"

# Set a translation for a specific language
php artisan translations:set welcome "Bun venit" --language=ro
```

#### Delete Translation

```bash
# Delete a translation (with confirmation)
php artisan translations:delete old_key

# Delete for a specific language
php artisan translations:delete old_key --language=ro

# Skip confirmation
php artisan translations:delete old_key --force
```

#### Clear Cache

```bash
# Clear cache for current language
php artisan translations:clear-cache

# Clear cache for a specific language
php artisan translations:clear-cache --language=ro

# Clear cache for all languages
php artisan translations:clear-cache --all

# Skip confirmation
php artisan translations:clear-cache --all --force
```

## API Reference

### TranslationsService

#### `all(): Collection`

Get all translations for the current language as a key-value collection.

#### `get(string $key, mixed $default = false): mixed`

Get a translation value by key. Returns the default value if the key doesn't exist.

#### `set(string $key, mixed $value = null): bool`

Set or update a translation value. Returns `true` if successful.

#### `setMany(array $translations): bool`

Set multiple translations at once. Returns `true` if successful.

#### `has(string $key): bool`

Check if a translation key exists.

#### `delete(string $key): bool`

Delete a translation by key. Returns `true` if successful.

#### `flushCache(): bool`

Clear the cache for the current language. Returns `true` if successful.

#### `flushAllCache(): bool` (static)

Clear the cache for all languages. Returns `true` if successful.

## Testing

```bash
composer test
```

## 📖 Resources

-   [Changelog](CHANGELOG.md) for more information on what has changed recently. ✍️

## 👥 Credits

-   [Rdcstarr](https://github.com/rdcstarr) 🙌

## 📜 License

-   [License](LICENSE.md) for more information. ⚖️
