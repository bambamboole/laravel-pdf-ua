# PDF/UA Proof of Concept - Architecture Documentation

## Overview

This document provides a detailed architectural overview of the PDF/UA (Universal Accessibility) proof of concept implementation using PHP and Laravel.

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      Application Layer                          │
│  ┌───────────────┐  ┌──────────────┐  ┌───────────────────┐    │
│  │  Artisan CMD  │  │    Facade    │  │  Direct Usage     │    │
│  │  pdf-ua:gen   │  │  PdfUa::     │  │  new Generator()  │    │
│  └───────┬───────┘  └──────┬───────┘  └─────────┬─────────┘    │
└──────────┼──────────────────┼────────────────────┼──────────────┘
           │                  │                    │
           └──────────────────┼────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                   Service Layer (PdfUaGenerator)                │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Configuration Management                                │  │
│  │  - Document metadata (title, author, subject, etc.)     │  │
│  │  - PDF settings (format, orientation, margins)          │  │
│  │  - Language and accessibility settings                  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Content Processing                                      │  │
│  │  - generateStructuredHtml()                              │  │
│  │    • Convert structured arrays to semantic HTML         │  │
│  │    • Support for h1-h3, p, ul, ol, table                │  │
│  │  - renderElement()                                       │  │
│  │    • Individual element rendering                       │  │
│  │  - renderTable(), renderListItems()                     │  │
│  │    • Specialized content renderers                      │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  PDF Generation                                          │  │
│  │  - generate()                                            │  │
│  │    • Create TCPDF instance                               │  │
│  │    • Set document metadata                               │  │
│  │    • Configure PDF/UA compliance settings                │  │
│  │    • Write HTML content                                  │  │
│  │    • Output to file or string                            │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      TCPDF Library Layer                        │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  PDF/UA Compliance Features                              │  │
│  │  - Tagged PDF structure                                  │  │
│  │  - Accessibility metadata                                │  │
│  │  - Unicode (UTF-8) support                               │  │
│  │  - Language specification                                │  │
│  │  - Reading order preservation                            │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  PDF Generation Engine                                   │  │
│  │  - HTML to PDF conversion                                │  │
│  │  - Font handling                                         │  │
│  │  - Page layout                                           │  │
│  │  - Image embedding                                       │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
                    ┌────────────────┐
                    │  PDF Document  │
                    │   (PDF/UA)     │
                    └────────────────┘
```

## Component Details

### 1. PdfUaGenerator Class

**Location**: `src/PdfUaGenerator.php`

**Responsibilities**:
- Manage PDF configuration and settings
- Convert structured content to HTML
- Generate PDF/UA compliant documents
- Handle document metadata

**Key Methods**:

```php
public function __construct(array $config = [])
// Initialize generator with configuration

public function generate(string $html, ?string $outputPath = null): string|bool
// Generate PDF from HTML content

public function generateStructuredHtml(array $content): string
// Convert structured content array to HTML

protected function renderElement(array $element): string
// Render individual content element

public function setConfig(array $config): self
// Update configuration

public function getConfig(): array
// Get current configuration
```

### 2. Laravel Service Provider

**Location**: `src/PdfUaServiceProvider.php`

**Responsibilities**:
- Register the package with Laravel
- Bind PdfUaGenerator to service container
- Register configuration, views, and commands
- Provide singleton instance for facade

**Registration**:
```php
$this->app->singleton('pdf-ua', function ($app) {
    return new PdfUaGenerator(config('laravel-pdf-ua', []));
});
```

### 3. Facade

**Location**: `src/Facades/PdfUa.php`

**Purpose**: Provide static access to PdfUaGenerator

**Usage**:
```php
PdfUa::generate($html, 'output.pdf');
PdfUa::setConfig(['title' => 'New Title']);
```

### 4. Artisan Command

**Location**: `src/Commands/GeneratePdfUaCommand.php`

**Command**: `php artisan pdf-ua:generate {output?}`

**Functionality**:
- Generate sample PDF/UA document
- Demonstrate all supported content types
- Provide example of proper PDF/UA structure

## Data Flow

### Content Generation Flow

```
User Input (Array)
    │
    ├── ['type' => 'h1', 'content' => 'Title']
    ├── ['type' => 'p', 'content' => 'Text']
    ├── ['type' => 'ul', 'items' => [...]]
    └── ['type' => 'table', 'headers' => [...], 'rows' => [...]]
    │
    ▼
generateStructuredHtml()
    │
    ├── Loop through each element
    ├── Call renderElement() for each
    │   │
    │   ├── Match element type
    │   ├── Apply htmlspecialchars() for security
    │   ├── Generate appropriate HTML tags
    │   └── Return HTML string
    │
    ▼
HTML String
    │
    ├── <h1>Title</h1>
    ├── <p>Text</p>
    ├── <ul><li>Item</li></ul>
    └── <table>...</table>
    │
    ▼
generate()
    │
    ├── Create TCPDF instance
    ├── Set document metadata
    │   ├── Title, Author, Subject
    │   ├── Keywords, Creator
    │   └── Language
    ├── Configure PDF/UA settings
    │   ├── PDF version 1.7
    │   ├── Language array
    │   └── Tagged PDF enabled
    ├── Write HTML to PDF
    └── Output to file/string
    │
    ▼
PDF/UA Document
```

## Configuration System

### Configuration File

**Location**: `config/laravel-pdf-ua.php`

**Structure**:
```php
return [
    // Document Information
    'title' => env('PDF_UA_TITLE', 'PDF/UA Document'),
    'author' => env('PDF_UA_AUTHOR', 'Laravel PDF/UA'),
    'subject' => env('PDF_UA_SUBJECT', 'Accessible PDF Document'),
    'keywords' => env('PDF_UA_KEYWORDS', 'PDF, UA, Accessibility'),
    'creator' => env('PDF_UA_CREATOR', 'Laravel PDF/UA Package'),
    'language' => env('PDF_UA_LANGUAGE', 'en-US'),
    
    // PDF Settings
    'pdf_version' => '1.7',
    'orientation' => 'P',
    'unit' => 'mm',
    'format' => 'A4',
    
    // Layout
    'margins' => [
        'left' => 15,
        'top' => 15,
        'right' => 15,
        'bottom' => 15,
    ],
];
```

### Environment Variables Support

```env
PDF_UA_TITLE="My Accessible Document"
PDF_UA_AUTHOR="John Doe"
PDF_UA_LANGUAGE="en-US"
```

## Content Types Support

### Headings (h1, h2, h3)

```php
['type' => 'h1', 'content' => 'Main Title']
['type' => 'h2', 'content' => 'Section Title']
['type' => 'h3', 'content' => 'Subsection Title']
```

### Paragraphs

```php
['type' => 'p', 'content' => 'Paragraph text content']
```

### Unordered Lists

```php
[
    'type' => 'ul',
    'items' => [
        'First item',
        'Second item',
        'Third item',
    ]
]
```

### Ordered Lists

```php
[
    'type' => 'ol',
    'items' => [
        'Step one',
        'Step two',
        'Step three',
    ]
]
```

### Tables

```php
[
    'type' => 'table',
    'headers' => ['Column 1', 'Column 2', 'Column 3'],
    'rows' => [
        ['Data 1A', 'Data 1B', 'Data 1C'],
        ['Data 2A', 'Data 2B', 'Data 2C'],
    ]
]
```

## PDF/UA Compliance Features

### 1. Tagged Structure

All content is properly tagged with semantic HTML elements that map to PDF structure tags:

- `<h1>` → H1 tag
- `<p>` → P tag
- `<ul>`, `<li>` → List tags
- `<table>`, `<th>`, `<td>` → Table tags

### 2. Document Metadata

Required metadata fields:
- **Title**: Document title for assistive technologies
- **Author**: Content creator identification
- **Language**: Primary language (e.g., en-US)
- **Subject**: Brief description
- **Keywords**: Search and categorization

### 3. Reading Order

Content flows in logical order:
1. Document title (h1)
2. Sections (h2)
3. Subsections (h3)
4. Paragraphs
5. Lists
6. Tables

### 4. Unicode Support

- UTF-8 encoding throughout
- Support for international characters
- Proper character escaping

## Testing Strategy

### 1. Structural Validation

```bash
php validate.php
```

Checks:
- File existence
- Namespace consistency
- Method availability
- Configuration structure

### 2. Syntax Validation

```bash
find src -name "*.php" -exec php -l {} \;
```

### 3. Unit Tests (When dependencies installed)

```bash
composer test
```

Tests:
- HTML generation from arrays
- PDF creation
- Configuration management
- Content rendering

## Extension Points

### Adding New Content Types

To add a new content type (e.g., 'blockquote'):

1. Add case to `renderElement()` method:
```php
match ($type) {
    // existing cases...
    'blockquote' => sprintf('<blockquote>%s</blockquote>', 
        htmlspecialchars($content)),
}
```

2. Document the new type in README

### Custom Styling

Future enhancement could include:

```php
[
    'type' => 'p',
    'content' => 'Styled text',
    'style' => [
        'font-size' => '14pt',
        'color' => '#333',
    ]
]
```

### Image Support

Future enhancement:

```php
[
    'type' => 'image',
    'src' => '/path/to/image.jpg',
    'alt' => 'Description for screen readers',
    'caption' => 'Image caption',
]
```

## Security Considerations

### 1. Input Sanitization

All user content is passed through `htmlspecialchars()`:
```php
htmlspecialchars($content)
```

### 2. File Path Validation

Output paths should be validated:
```php
// Future enhancement
if (!$this->isValidPath($outputPath)) {
    throw new InvalidArgumentException('Invalid output path');
}
```

### 3. Content Injection Prevention

Using structured arrays prevents HTML/script injection:
```php
// Safe: array input
['type' => 'p', 'content' => '<script>alert("xss")</script>']

// Renders as: <p>&lt;script&gt;alert("xss")&lt;/script&gt;</p>
```

## Performance Considerations

### Memory Usage

- TCPDF loads into memory
- Large documents require adequate memory_limit
- Recommended: 128M+ for complex documents

### Generation Time

Factors affecting generation time:
- Document length
- Number of images
- Complex tables
- Font subsetting

### Optimization Strategies

1. **Batch Processing**: Generate multiple PDFs in queue
2. **Caching**: Cache frequently used templates
3. **Lazy Loading**: Load resources only when needed
4. **Resource Limits**: Set timeouts for long operations

## Deployment Considerations

### Requirements

- PHP 8.2+
- TCPDF library (tecnickcom/tcpdf ^6.7)
- Laravel 11.0+ (for Laravel integration)
- Memory: 128M+ recommended
- Temp directory with write permissions

### Installation Steps

1. Install via Composer:
```bash
composer require bambamboole/laravel-pdf-ua
```

2. Publish configuration:
```bash
php artisan vendor:publish --tag=laravel-pdf-ua-config
```

3. Set environment variables:
```env
PDF_UA_TITLE="Company Documents"
PDF_UA_AUTHOR="Your Company"
PDF_UA_LANGUAGE="en-US"
```

## Future Roadmap

### Phase 1: Enhanced Content Support
- Image support with alt text
- Link annotations
- Form fields
- Math equations

### Phase 2: Validation & Compliance
- Built-in PDF/UA validator
- Compliance reports
- Accessibility checker integration

### Phase 3: Advanced Features
- PDF/A support (archival)
- Digital signatures
- Encryption options
- Watermarks

### Phase 4: Performance & Scale
- Async generation
- Queue integration
- Distributed processing
- CDN integration

## Conclusion

This architecture provides a solid foundation for PDF/UA document generation in PHP/Laravel applications. The modular design allows for easy extension while maintaining compliance with accessibility standards.

The use of TCPDF ensures proper tagged PDF generation, while the structured content approach provides a clean, maintainable API for developers.
