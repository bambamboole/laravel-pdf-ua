# Quick Start Guide - PDF/UA with Laravel

Get up and running with PDF/UA document generation in 5 minutes!

## What You'll Build

A simple Laravel application that generates an accessible PDF document with:
- Proper heading structure
- Paragraphs of text
- Lists
- Tables
All compliant with PDF/UA (Universal Accessibility) standards.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 11.0+ (optional, can be used standalone)

## Step 1: Install the Package

```bash
composer require bambamboole/laravel-pdf-ua
```

## Step 2: Publish Configuration (Laravel only)

```bash
php artisan vendor:publish --tag=laravel-pdf-ua-config
```

This creates `config/laravel-pdf-ua.php` with default settings.

## Step 3: Generate Your First PDF

### Option A: Using Artisan Command (Laravel)

```bash
php artisan pdf-ua:generate storage/app/my-first-pdf.pdf
```

This generates a sample PDF with all supported content types!

### Option B: In Your Code (Laravel)

```php
<?php

namespace App\Http\Controllers;

use Bambamboole\LaravelPdfUa\Facades\PdfUa;

class PdfController extends Controller
{
    public function generate()
    {
        $html = '<h1>My Accessible Document</h1>
                 <p>This is a PDF/UA compliant document.</p>';
        
        $path = storage_path('app/document.pdf');
        PdfUa::generate($html, $path);
        
        return response()->download($path);
    }
}
```

### Option C: Structured Content (Recommended)

```php
<?php

use Bambamboole\LaravelPdfUa\PdfUaGenerator;

$generator = new PdfUaGenerator([
    'title' => 'Monthly Report',
    'author' => 'Your Company',
    'language' => 'en-US',
]);

$content = [
    [
        'type' => 'h1',
        'content' => 'Monthly Sales Report',
    ],
    [
        'type' => 'h2',
        'content' => 'Executive Summary',
    ],
    [
        'type' => 'p',
        'content' => 'This report provides an overview of sales performance for the current month.',
    ],
    [
        'type' => 'h2',
        'content' => 'Key Metrics',
    ],
    [
        'type' => 'ul',
        'items' => [
            'Total Revenue: $125,000',
            'New Customers: 45',
            'Customer Satisfaction: 94%',
        ],
    ],
    [
        'type' => 'h2',
        'content' => 'Sales by Region',
    ],
    [
        'type' => 'table',
        'headers' => ['Region', 'Sales', 'Growth'],
        'rows' => [
            ['North', '$45,000', '+12%'],
            ['South', '$38,000', '+8%'],
            ['East', '$25,000', '+15%'],
            ['West', '$17,000', '+5%'],
        ],
    ],
];

$html = $generator->generateStructuredHtml($content);
$generator->generate($html, 'monthly-report.pdf');

echo "PDF generated successfully!\n";
```

### Option D: Standalone (No Laravel)

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bambamboole\LaravelPdfUa\PdfUaGenerator;

$generator = new PdfUaGenerator([
    'title' => 'Standalone Document',
    'author' => 'PHP Developer',
]);

$html = '<h1>Hello World</h1><p>This is a standalone PDF/UA document.</p>';
$generator->generate($html, 'standalone.pdf');
```

## Step 4: Customize Configuration

Edit `config/laravel-pdf-ua.php` or use environment variables:

```env
# .env file
PDF_UA_TITLE="Company Documents"
PDF_UA_AUTHOR="Your Company Name"
PDF_UA_LANGUAGE="en-US"
PDF_UA_SUBJECT="Accessible Documents"
```

## Step 5: Test Your PDF

### View in Adobe Acrobat
1. Open the generated PDF
2. Go to Tools → Accessibility → Full Check
3. Verify PDF/UA compliance

### Test with Screen Reader
1. Open PDF in Adobe Reader
2. Enable Read Aloud (View → Read Out Loud → Activate)
3. Listen to the document structure

### Use Free Checker
1. Download PAC (PDF Accessibility Checker)
2. Open your PDF in PAC
3. Run accessibility check

## Common Use Cases

### 1. Invoice Generation

```php
$content = [
    ['type' => 'h1', 'content' => 'Invoice #12345'],
    ['type' => 'p', 'content' => 'Date: ' . date('Y-m-d')],
    ['type' => 'h2', 'content' => 'Bill To'],
    ['type' => 'p', 'content' => 'Customer Name'],
    ['type' => 'h2', 'content' => 'Items'],
    [
        'type' => 'table',
        'headers' => ['Item', 'Quantity', 'Price', 'Total'],
        'rows' => [
            ['Product A', '2', '$50.00', '$100.00'],
            ['Product B', '1', '$75.00', '$75.00'],
        ],
    ],
];
```

### 2. Report Generation

```php
$content = [
    ['type' => 'h1', 'content' => 'Annual Report 2024'],
    ['type' => 'h2', 'content' => 'Overview'],
    ['type' => 'p', 'content' => 'Summary of the year...'],
    ['type' => 'h2', 'content' => 'Achievements'],
    [
        'type' => 'ul',
        'items' => [
            'Launched 3 new products',
            'Expanded to 5 new markets',
            'Grew revenue by 25%',
        ],
    ],
];
```

### 3. Certificate Generation

```php
$generator = new PdfUaGenerator([
    'title' => 'Certificate of Completion',
    'author' => 'Training Academy',
    'orientation' => 'L', // Landscape
]);

$content = [
    ['type' => 'h1', 'content' => 'Certificate of Completion'],
    ['type' => 'p', 'content' => 'This certifies that'],
    ['type' => 'h2', 'content' => 'John Doe'],
    ['type' => 'p', 'content' => 'has successfully completed'],
    ['type' => 'h2', 'content' => 'PDF/UA Training Course'],
    ['type' => 'p', 'content' => 'Date: ' . date('F j, Y')],
];
```

## Supported Content Types

| Type | Usage | Example |
|------|-------|---------|
| `h1` | Main heading | `['type' => 'h1', 'content' => 'Title']` |
| `h2` | Section heading | `['type' => 'h2', 'content' => 'Section']` |
| `h3` | Subsection heading | `['type' => 'h3', 'content' => 'Subsection']` |
| `p` | Paragraph | `['type' => 'p', 'content' => 'Text']` |
| `ul` | Unordered list | `['type' => 'ul', 'items' => ['A', 'B']]` |
| `ol` | Ordered list | `['type' => 'ol', 'items' => ['1', '2']]` |
| `table` | Data table | `['type' => 'table', 'headers' => [...]]` |

## Configuration Options

```php
$generator = new PdfUaGenerator([
    // Document metadata
    'title' => 'Document Title',        // Required for PDF/UA
    'author' => 'Author Name',          // Recommended
    'subject' => 'Document Subject',    // Optional
    'keywords' => 'keyword1, keyword2', // Optional
    'language' => 'en-US',              // Required for PDF/UA
    
    // PDF settings
    'orientation' => 'P',  // P=Portrait, L=Landscape
    'format' => 'A4',      // A4, Letter, Legal, etc.
    'unit' => 'mm',        // mm, cm, in, pt
    
    // PDF version
    'pdf_version' => '1.7', // For PDF/UA compliance
]);
```

## Troubleshooting

### Issue: "Class not found"
**Solution**: Run `composer dump-autoload`

### Issue: "Memory limit exceeded"
**Solution**: Increase PHP memory limit in `php.ini`:
```ini
memory_limit = 256M
```

### Issue: "Cannot write to file"
**Solution**: Check directory permissions:
```bash
chmod 755 storage/app
```

### Issue: "TCPDF not found"
**Solution**: Install dependencies:
```bash
composer install
```

## Next Steps

1. **Read the Documentation**
   - `README.md` - Full documentation
   - `DEMO.md` - Feature demonstrations
   - `ARCHITECTURE.md` - Technical details

2. **Explore Examples**
   - Run `php example.php` for a complete example
   - Check `src/Commands/GeneratePdfUaCommand.php` for more examples

3. **Test Accessibility**
   - Generate PDFs and test with screen readers
   - Validate with PAC or Adobe Acrobat

4. **Customize**
   - Add your own content types
   - Create reusable templates
   - Integrate with your application

## Getting Help

- **Documentation**: Check README.md and ARCHITECTURE.md
- **Examples**: See example.php and the artisan command
- **Issues**: Report bugs on GitHub
- **Validation**: Run `php validate.php` to check setup

## Resources

- PDF/UA Standard: https://www.pdfa.org/resource/pdfua/
- TCPDF Documentation: https://tcpdf.org/docs/
- Accessibility Guidelines: https://www.w3.org/WAI/WCAG21/quickref/

## Success! 🎉

You now have a working PDF/UA document generation system. Your PDFs are:

- ✅ Accessible to screen readers
- ✅ Standards compliant (PDF/UA)
- ✅ Properly structured
- ✅ Ready for production use

Happy PDF generating!
