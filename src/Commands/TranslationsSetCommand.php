<?php

namespace Rdcstarr\Translations\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use function Laravel\Prompts\text;

class TranslationsSetCommand extends Command
{
	protected $signature = 'translations:set
		{key? : The translation key}
		{value? : The translation value}
		{--language= : The language code (e.g., en, ro)}';

	protected $description = 'Set a translation value';

	public function handle(): void
	{
		$key = $this->argument('key')
			?: text('Enter the translation key', 'e.g., welcome', required: true)
			?: throw new InvalidArgumentException('Translation key is required.');

		$value = $this->argument('value')
			?: text('Enter the translation value', 'e.g., Welcome!', required: true)
			?: throw new InvalidArgumentException('Translation value is required.');

		$language = $this->option('language') ?: app()->getLocale();

		if (translations(languageCode: $language)->set($key, $value))
		{
			$this->components->success("Translation '{$key}' has been set for language '{$language}'.");
			return;
		}

		$this->components->warn('Translation was not updated (value unchanged or operation failed).');
	}
}
