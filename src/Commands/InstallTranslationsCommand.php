<?php

namespace Rdcstarr\Translations\Commands;

use Artisan;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\confirm;

class InstallTranslationsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	public $signature = 'translations:install
		{--force : Force the installation without confirmation}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	public $description = 'Install the translations package';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		if (!Schema::hasTable('languages'))
		{
			$this->components->error('The "languages" table does not exist. Please install the laravel-languages package first:');
			$this->line('  composer require rdcstarr/laravel-languages');
			$this->line('  php artisan languages:install');
			return;
		}

		if (!$this->option('force'))
		{
			if (!confirm('This will publish and run the migrations. Do you want to continue?'))
			{
				$this->components->warn('Installation cancelled.');
				return;
			}
		}

		$this->components->info('Starting Translations Package Installation...');

		$steps = [
			'📄 Publishing migrations' => 'publishMigrations',
			'🏁 Running migrations'    => 'runMigrations',
		];

		foreach ($steps as $name => $method)
		{
			try
			{
				$this->components->task($name, fn() => $this->{$method}());
			}
			catch (Exception $e)
			{
				$this->components->error($name . ' failed: ' . $e->getMessage());
				return;
			}
		}

		$this->components->success('Translations Package Installation Completed Successfully!');
	}

	/**
	 * Publish the migrations.
	 */
	protected function publishMigrations(): void
	{
		Artisan::call('vendor:publish', [
			'--provider' => 'Rdcstarr\Translations\TranslationsServiceProvider',
			'--tag'      => 'laravel-translations-migrations',
		]);
	}

	/**
	 * Run the migrations.
	 */
	protected function runMigrations(): void
	{
		Artisan::call('migrate');
	}
}
