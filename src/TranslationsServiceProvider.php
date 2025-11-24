<?php

namespace Rdcstarr\Translations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rdcstarr\Translations\Commands\TranslationsClearCacheCommand;
use Rdcstarr\Translations\Commands\TranslationsDeleteCommand;
use Rdcstarr\Translations\Commands\TranslationsGetCommand;
use Rdcstarr\Translations\Commands\TranslationsListCommand;
use Rdcstarr\Translations\Commands\TranslationsSetCommand;

class TranslationsServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		/*
		 * This class is a Package Service Provider
		 *
		 * More info: https://github.com/spatie/laravel-package-tools
		 */
		$package
			->name('laravel-translations')
			->hasConfigFile()
			->hasViews()
			->hasMigration('create_laravel_translations_table')
			->hasCommands([
				TranslationsListCommand::class,
				TranslationsGetCommand::class,
				TranslationsSetCommand::class,
				TranslationsDeleteCommand::class,
				TranslationsClearCacheCommand::class,
			]);
	}

	public function register(): void
	{
		parent::register();

		$this->app->bind(TranslationsService::class, fn($app) => new TranslationsService($app->getLocale()));
	}
}
