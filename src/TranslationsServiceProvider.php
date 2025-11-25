<?php

namespace Rdcstarr\Translations;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rdcstarr\Translations\Commands\TranslationsClearCacheCommand;
use Rdcstarr\Translations\Commands\TranslationsDeleteCommand;
use Rdcstarr\Translations\Commands\TranslationsGetCommand;
use Rdcstarr\Translations\Commands\TranslationsListCommand;
use Rdcstarr\Translations\Commands\TranslationsSetCommand;

class TranslationsServiceProvider extends PackageServiceProvider
{
	/*
	 * This class is a Package Service Provider
	 *
	 * More info: https://github.com/spatie/laravel-package-tools
	 */
	public function configurePackage(Package $package): void
	{
		$package
			->name('laravel-translations')
			->discoversMigrations()
			->runsMigrations()
			->hasCommands([
				TranslationsListCommand::class,
				TranslationsGetCommand::class,
				TranslationsSetCommand::class,
				TranslationsDeleteCommand::class,
				TranslationsClearCacheCommand::class,
			])
			->hasInstallCommand(function (InstallCommand $command)
			{
				$command
					->publishMigrations()
					->askToRunMigrations();
			});
	}

	public function register(): void
	{
		parent::register();

		$this->app->bind(TranslationsService::class, fn($app) => new TranslationsService($app->getLocale()));
	}
}
