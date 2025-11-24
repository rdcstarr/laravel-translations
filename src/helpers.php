<?php

use Rdcstarr\Translations\TranslationsService;

if (!function_exists('translations'))
{
	function translations(?string $key = null, mixed $default = false, ?string $languageCode = null): mixed
	{
		$service = $languageCode
			? new TranslationsService($languageCode)
			: app(TranslationsService::class);

		return $key ? $service->get($key, $default) : $service;
	}
}

if (!function_exists('_t'))
{
	function _t(?string $key = null, mixed $default = false, ?string $languageCode = null): mixed
	{
		$service = $languageCode
			? new TranslationsService($languageCode)
			: app(TranslationsService::class);

		return $key ? $service->get($key, $default) : $service;
	}
}
