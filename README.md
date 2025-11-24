# Laravel translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rdcstarr/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/rdcstarr/laravel-translations) [![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/rdcstarr/laravel-translations/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rdcstarr/laravel-translations/actions?query=workflow%3Arun-tests+branch%3Amain) [![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rdcstarr/laravel-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rdcstarr/laravel-translations/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) [![Total Downloads](https://img.shields.io/packagist/dt/rdcstarr/laravel-translations.svg?style=flat-square)](https://packagist.org/packages/rdcstarr/laravel-translations)

## Installation

You can install the package via composer:

```bash
composer require rdcstarr/laravel-translations
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-translations-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-translations-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-translations-views"
```

## Usage

```php
$Translations = new Rdcstarr\Translations();
echo $Translations->echoPhrase('Hello, Rdcstarr!');
```

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
# laravel-translations
