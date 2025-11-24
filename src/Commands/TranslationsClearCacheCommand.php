<?php

namespace Rdcstarr\Translations\Commands;

use Illuminate\Console\Command;
use Rdcstarr\Translations\TranslationsService;
use function Laravel\Prompts\confirm;

class TranslationsClearCacheCommand extends Command
{
	protected $signature = 'translations:clear-cache
		{--all : Clear cache for all languages}
		{--language= : The language code (e.g., en, ro)}
		{--force : Skip confirmation prompt}';

	protected $description = 'Clear translations cache';

	public function handle(): void
	{
		$clearAll = $this->option('all');
		$language = $this->option('language') ?: app()->getLocale();

		$message = $clearAll
			? 'Are you sure you want to clear the translations cache for all languages?'
			: "Are you sure you want to clear the translations cache for language '{$language}'?";

		if (!$this->option('force') && !confirm($message, false))
		{
			$this->components->warn('Operation cancelled.');
			return;
		}

		if ($clearAll)
		{
			if (!TranslationsService::flushAllCache())
			{
				$this->components->error('Failed to clear translations cache for all languages.');
				return;
			}

			$this->components->success('Translations cache has been cleared for all languages.');
			return;
		}

		if (!translations(languageCode: $language)->flushCache())
		{
			$this->components->error("Failed to clear translations cache for language '{$language}'.");
			return;
		}

		$this->components->success("Translations cache has been cleared for language '{$language}'.");
	}
}
