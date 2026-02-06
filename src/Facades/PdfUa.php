<?php

namespace Bambamboole\LaravelPdfUa\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bambamboole\LaravelPdfUa\PdfUaGenerator
 */
class PdfUa extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pdf-ua';
    }
}
