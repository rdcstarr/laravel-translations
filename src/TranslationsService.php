<?php

namespace Rdcstarr\Translations;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Rdcstarr\Languages\Models\Language;
use Rdcstarr\Translations\Models\Translation;
use Throwable;

class TranslationsService
{
	public function __construct(
		protected string $languageCode = 'en',
	) {
		//
	}

	/**
	 * Retrieve all translations from the cache or database.
	 *
	 * @return Collection A collection of all translations as key-value pairs.
	 */
	public function all(): Collection
	{
		try
		{
			return Cache::rememberForever(
				$this->cacheKey(),
				fn() => Translation::byLanguageCode($this->languageCode)->pluck('value', 'key')
			);
		}
		catch (Throwable $e)
		{
			report($e);
			return collect();
		}
	}

	/**
	 * Retrieve a translation value by key.
	 *
	 * @param string $key The translation key to retrieve.
	 * @param mixed $default Default value to return if key doesn't exist (default: false).
	 * @return mixed The translation value or default.
	 */
	public function get(string $key, mixed $default = false): mixed
	{
		return filled($key) ? $this->all()->get($key, $default) : false;
	}

	/**
	 * Update or create a translation value.
	 *
	 * @param string $key The translation key.
	 * @param mixed $value The value to set.
	 * @return bool True if the value was updated, false otherwise.
	 */
	public function set(string $key, mixed $value = null): bool
	{
		if (blank($key))
		{
			return false;
		}

		try
		{
			$language = $this->getLanguage();

			if (!$language || $value === $this->get($key, null))
			{
				return false;
			}

			Translation::updateOrCreate(
				['language_id' => $language->id, 'key' => $key],
				['value' => $value]
			);

			return $this->flushCache();
		}
		catch (Throwable $e)
		{
			report($e);
			return false;
		}
	}

	/**
	 * Set multiple translations in a single batch operation.
	 *
	 * @param array $translations An associative array of key-value pairs.
	 * @return bool True if all translations were updated, false otherwise.
	 */
	public function setMany(array $translations): bool
	{
		if (empty($translations))
		{
			return false;
		}

		try
		{
			$language = $this->getLanguage();

			if (!$language)
			{
				return false;
			}

			$data = collect($translations)
				->filter(fn($value, $key) => filled($key))
				->map(fn($value, $key) => [
					'language_id' => $language->id,
					'key'         => $key,
					'value'       => $value,
					'created_at'  => now(),
					'updated_at'  => now(),
				])
				->values()
				->all();

			if (empty($data))
			{
				return false;
			}

			Translation::upsert($data, ['language_id', 'key'], ['value', 'updated_at']);

			return $this->flushCache();
		}
		catch (Throwable $e)
		{
			report($e);
			return false;
		}
	}

	/**
	 * Check if a translation exists.
	 *
	 * @param string $key The translation key to check.
	 * @return bool True if the key exists, false otherwise.
	 */
	public function has(string $key): bool
	{
		return filled($key) && $this->all()->has($key);
	}

	/**
	 * Remove a translation from storage.
	 *
	 * @param string $key The translation key to remove.
	 * @return bool True if deleted, false otherwise.
	 */
	public function delete(string $key): bool
	{
		if (blank($key))
		{
			return false;
		}

		try
		{
			$language = $this->getLanguage();

			if (!$language)
			{
				return false;
			}

			$deleted = Translation::where('language_id', $language->id)
				->where('key', $key)
				->delete();

			return $deleted > 0 ? $this->flushCache() : false;
		}
		catch (Throwable $e)
		{
			report($e);
			return false;
		}
	}

	/**
	 * Clear the translations cache for the current language.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flushCache(): bool
	{
		try
		{
			return Cache::forget($this->cacheKey());
		}
		catch (Throwable $e)
		{
			report($e);
			return false;
		}
	}

	/**
	 * Clear all translations cache for all languages.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function flushAllCache(): bool
	{
		try
		{
			Language::pluck('code')->each(
				fn($code) => Cache::forget("translations.lang.{$code}")
			);

			return true;
		}
		catch (Throwable $e)
		{
			report($e);
			return false;
		}
	}

	protected function cacheKey(): string
	{
		return "translations.lang.{$this->languageCode}";
	}

	protected function getLanguage(): ?Language
	{
		try
		{
			return Language::where('code', $this->languageCode)->first();
		}
		catch (Throwable $e)
		{
			report($e);
			return null;
		}
	}
}
