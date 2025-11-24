<?php

namespace Rdcstarr\Translations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rdcstarr\Translations\TranslationsService
 */
class Translations extends Facade
{
	protected static function getFacadeAccessor(): string
	{
		return \Rdcstarr\Translations\TranslationsService::class;
	}
}
