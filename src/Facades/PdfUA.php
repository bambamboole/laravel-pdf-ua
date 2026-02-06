<?php

namespace Bambamboole\PdfUA\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bambamboole\PdfUA\PdfUA
 */
class PdfUA extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bambamboole\PdfUA\PdfUA::class;
    }
}
