<?php

namespace Rdcstarr\Translations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rdcstarr\Translations\Commands\InstallTranslationsCommand;
use Rdcstarr\Translations\Commands\TranslationsClearCacheCommand;
use Rdcstarr\Translations\Commands\TranslationsDeleteCommand;
use Rdcstarr\Translations\Commands\TranslationsGetCommand;
use Rdcstarr\Translations\Commands\TranslationsListCommand;
use Rdcstarr\Translations\Commands\TranslationsSetCommand;

class TranslationsServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		$package
			->name('laravel-translations')
			->hasMigration('create_translations_table')
			->hasCommands([
				InstallTranslationsCommand::class,
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
