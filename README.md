# Laravel PDF/UA - Universal Accessibility PDF Generation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bambamboole/laravel-pdf-ua.svg?style=flat-square)](https://packagist.org/packages/bambamboole/laravel-pdf-ua)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bambamboole/laravel-pdf-ua/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bambamboole/laravel-pdf-ua/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bambamboole/laravel-pdf-ua.svg?style=flat-square)](https://packagist.org/packages/bambamboole/laravel-pdf-ua)

A Laravel package for creating **PDF/UA (Universal Accessibility)** compliant PDFs with structured information using PHP and TCPDF library.

## What is PDF/UA?

PDF/UA (ISO 14289-1) is an international standard for creating accessible PDF documents that can be read by assistive technologies like screen readers. This package provides a proof of concept for generating PDF/UA compliant documents with:

- **Proper document structure** with semantic tags (headings, paragraphs, lists, tables)
- **Document metadata** (title, author, language, subject, keywords)
- **Logical reading order** for screen readers
- **Tagged content** for accessibility
- **Language specification** for proper text-to-speech

## Installation

You can install the package via composer:

```bash
composer require bambamboole/laravel-pdf-ua
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-pdf-ua-config"
```

This is the contents of the published config file:

```php
return [
    'title' => env('PDF_UA_TITLE', 'PDF/UA Document'),
    'author' => env('PDF_UA_AUTHOR', 'Laravel PDF/UA'),
    'subject' => env('PDF_UA_SUBJECT', 'Accessible PDF Document'),
    'keywords' => env('PDF_UA_KEYWORDS', 'PDF, UA, Accessibility'),
    'creator' => env('PDF_UA_CREATOR', 'Laravel PDF/UA Package'),
    'language' => env('PDF_UA_LANGUAGE', 'en-US'),
    'pdf_version' => '1.7',
    'orientation' => 'P', // P=Portrait, L=Landscape
    'unit' => 'mm',
    'format' => 'A4',
    'margins' => [
        'left' => 15,
        'top' => 15,
        'right' => 15,
        'bottom' => 15,
    ],
];
```

## Usage

### Quick Start - Generate Sample PDF

Generate a sample PDF/UA document using the artisan command:

```bash
php artisan pdf-ua:generate
```

This will create a sample PDF at `storage/app/sample-pdf-ua.pdf`.

You can specify a custom output path:

```bash
php artisan pdf-ua:generate /path/to/output.pdf
```

### Programmatic Usage

#### Basic Usage

```php
use Bambamboole\LaravelPdfUa\PdfUaGenerator;

$generator = new PdfUaGenerator([
    'title' => 'My Accessible Document',
    'author' => 'John Doe',
    'subject' => 'Important Information',
    'language' => 'en-US',
]);

$html = '<h1>Welcome</h1><p>This is an accessible PDF document.</p>';
$pdf = $generator->generate($html, 'output.pdf');
```

#### Using Structured Content

```php
use Bambamboole\LaravelPdfUa\PdfUaGenerator;

$generator = new PdfUaGenerator();

$content = [
    [
        'type' => 'h1',
        'content' => 'Document Title',
    ],
    [
        'type' => 'h2',
        'content' => 'Section Heading',
    ],
    [
        'type' => 'p',
        'content' => 'This is a paragraph with important information.',
    ],
    [
        'type' => 'ul',
        'items' => [
            'First bullet point',
            'Second bullet point',
            'Third bullet point',
        ],
    ],
    [
        'type' => 'table',
        'headers' => ['Name', 'Value', 'Description'],
        'rows' => [
            ['Item 1', '100', 'First item description'],
            ['Item 2', '200', 'Second item description'],
        ],
    ],
];

$html = $generator->generateStructuredHtml($content);
$pdf = $generator->generate($html, 'structured-document.pdf');
```

#### Using the Facade

```php
use Bambamboole\LaravelPdfUa\Facades\PdfUa;

$html = '<h1>Accessible Document</h1><p>Generated with facade.</p>';
$pdf = PdfUa::generate($html, 'document.pdf');
```

### Supported Content Types

The package supports the following structured content types:

- `h1`, `h2`, `h3` - Headings
- `p` - Paragraphs
- `ul` - Unordered lists
- `ol` - Ordered lists
- `table` - Data tables with headers

### Configuration Options

You can customize the PDF generation by passing configuration options:

```php
$generator = new PdfUaGenerator([
    'title' => 'Document Title',
    'author' => 'Author Name',
    'subject' => 'Document Subject',
    'keywords' => 'keyword1, keyword2, keyword3',
    'creator' => 'My Application',
    'language' => 'en-US', // Language code (en-US, de-DE, fr-FR, etc.)
    'pdf_version' => '1.7',
    'orientation' => 'P', // P=Portrait, L=Landscape
    'unit' => 'mm',
    'format' => 'A4',
]);
```

## Features

### ✅ PDF/UA Compliance

- **Tagged PDF structure** for screen readers
- **Document metadata** required by PDF/UA standard
- **Language specification** for proper text-to-speech
- **Semantic HTML** structure (h1-h6, p, ul, ol, table)
- **Logical reading order**

### 🎨 Structured Content

- Easy-to-use array-based content definition
- Support for headings, paragraphs, lists, and tables
- Automatic HTML generation from structured data

### ⚙️ Flexible Configuration

- Customizable document metadata
- Multiple page formats and orientations
- Configurable margins and units
- Environment-based configuration support

## Technical Details

This package uses [TCPDF](https://tcpdf.org/) for PDF generation, which provides:

- PDF/UA compliance features
- Tagged PDF support
- Unicode support (UTF-8)
- Multiple language support
- Standards-compliant PDF generation

## Testing

```bash
composer test
```

## Limitations & Future Improvements

This is a **proof of concept** demonstrating PDF/UA generation with PHP. Future enhancements could include:

- [ ] Image support with alternative text
- [ ] Form fields with proper labels
- [ ] More complex table structures (colspan, rowspan)
- [ ] Custom styling options
- [ ] PDF/A support (archival standard)
- [ ] Enhanced validation of PDF/UA compliance
- [ ] Additional languages and right-to-left text support

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [bambamboole](https://github.com/bambamboole)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Resources

- [PDF/UA Standard (ISO 14289-1)](https://www.iso.org/standard/64599.html)
- [TCPDF Library](https://tcpdf.org/)
- [Web Content Accessibility Guidelines (WCAG)](https://www.w3.org/WAI/standards-guidelines/wcag/)
- [PDF Association - PDF/UA](https://www.pdfa.org/resource/pdfua/)
