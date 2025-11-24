<?php

namespace Rdcstarr\Translations\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class TranslationsDeleteCommand extends Command
{
	protected $signature = 'translations:delete
		{key? : The translation key}
		{--language= : The language code (e.g., en, ro)}
		{--force : Skip confirmation prompt}';

	protected $description = 'Delete a translation';

	public function handle(): void
	{
		$key = $this->argument('key')
			?: text('Enter the translation key to delete', 'e.g., welcome', required: true)
			?: throw new InvalidArgumentException('Translation key is required.');

		$language = $this->option('language') ?: app()->getLocale();

		$service = translations(languageCode: $language);

		if (!$service->has($key))
		{
			$this->components->warn("The translation '{$key}' does not exist for language '{$language}'.");
			return;
		}

		if (!$this->option('force') && !confirm("Are you sure you want to delete the translation '{$key}' for language '{$language}'?", false))
		{
			$this->components->error('Operation cancelled.');
			return;
		}

		if (!$service->delete($key))
		{
			$this->components->error('Failed to delete the translation.');
			return;
		}

		$this->components->success("The translation '{$key}' has been deleted for language '{$language}'.");
	}
}
