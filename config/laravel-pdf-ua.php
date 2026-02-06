<?php

// config for Bambamboole/LaravelPdfUa
return [
    /*
    |--------------------------------------------------------------------------
    | PDF Document Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for PDF/UA document generation
    |
    */

    'title' => env('PDF_UA_TITLE', 'PDF/UA Document'),

    'author' => env('PDF_UA_AUTHOR', 'Laravel PDF/UA'),

    'subject' => env('PDF_UA_SUBJECT', 'Accessible PDF Document'),

    'keywords' => env('PDF_UA_KEYWORDS', 'PDF, UA, Accessibility'),

    'creator' => env('PDF_UA_CREATOR', 'Laravel PDF/UA Package'),

    'language' => env('PDF_UA_LANGUAGE', 'en-US'),

    /*
    |--------------------------------------------------------------------------
    | PDF Format Settings
    |--------------------------------------------------------------------------
    |
    | Configure the PDF format, orientation, and unit of measurement
    |
    */

    'pdf_version' => '1.7',

    'orientation' => 'P', // P=Portrait, L=Landscape

    'unit' => 'mm',

    'format' => 'A4',

    /*
    |--------------------------------------------------------------------------
    | Margins
    |--------------------------------------------------------------------------
    |
    | Set the default margins for the PDF document (in mm)
    |
    */

    'margins' => [
        'left' => 15,
        'top' => 15,
        'right' => 15,
        'bottom' => 15,
    ],
];
