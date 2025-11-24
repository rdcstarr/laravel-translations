<?php

namespace Rdcstarr\Translations\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\text;

class TranslationsListCommand extends Command
{
	protected $signature = 'translations:list {language? : The language code (e.g., en, ro)}';

	protected $description = 'List all translations for a specific language';

	public function handle(): void
	{
		$language = $this->argument('language')
			?: text('Enter the language code', app()->getLocale(), required: true);

		$translations = translations(languageCode: $language)->all();

		if ($translations->isEmpty())
		{
			$this->components->warn("No translations found for language '{$language}'.");
			return;
		}

		$this->components->info("All translations for '{$language}':");
		$this->table(
			['Key', 'Value', 'Type'],
			$translations->map(fn($value, $key) => [
				'Key'   => $key,
				'Value' => is_scalar($value) ? (string) $value : json_encode($value),
				'Type'  => gettype($value),
			])->values()->toArray()
		);
	}
}
