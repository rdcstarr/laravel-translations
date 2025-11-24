<?php

namespace Rdcstarr\Translations\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use function Laravel\Prompts\text;

class TranslationsGetCommand extends Command
{
	protected $signature = 'translations:get
		{key? : The translation key}
		{--language= : The language code (e.g., en, ro)}';

	protected $description = 'Get a translation value';

	public function handle(): void
	{
		$key = $this->argument('key')
			?: text('Enter the translation key to get', 'e.g., welcome', required: true)
			?: throw new InvalidArgumentException('Translation key is required.');

		$language = $this->option('language') ?: app()->getLocale();

		$service = translations(languageCode: $language);

		if (!$service->has($key))
		{
			$this->components->warn("The translation '{$key}' does not exist for language '{$language}'.");
			return;
		}

		$value = $service->get($key);

		$this->components->info("Translation '{$key}' ({$language}):");
		$this->line('  ' . (is_scalar($value) ? (string) $value : json_encode($value, JSON_PRETTY_PRINT)));
	}
}
