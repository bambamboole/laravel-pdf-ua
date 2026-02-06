<?php

/**
 * Standalone Example - PDF/UA Generation
 * 
 * This example demonstrates PDF/UA document generation without Laravel.
 * Run with: php example.php
 */

require_once __DIR__ . '/src/PdfUaGenerator.php';

use Bambamboole\LaravelPdfUa\PdfUaGenerator;

echo "PDF/UA Proof of Concept - Generating Sample Document...\n\n";

try {
    // Create a new PDF/UA generator with custom configuration
    $generator = new PdfUaGenerator([
        'title' => 'PDF/UA Proof of Concept',
        'author' => 'Laravel PDF/UA Package',
        'subject' => 'Demonstration of PDF/UA Accessibility Features',
        'keywords' => 'PDF, Universal Accessibility, Structured Content, PHP, TCPDF',
        'language' => 'en-US',
    ]);

    // Define structured content
    $content = [
        [
            'type' => 'h1',
            'content' => 'PDF/UA Proof of Concept',
        ],
        [
            'type' => 'p',
            'content' => 'This document demonstrates PDF/UA (Universal Accessibility) generation using PHP and the TCPDF library.',
        ],
        [
            'type' => 'h2',
            'content' => 'What is PDF/UA?',
        ],
        [
            'type' => 'p',
            'content' => 'PDF/UA (ISO 14289-1) is an ISO standard for creating accessible PDF documents that can be read by assistive technologies such as screen readers.',
        ],
        [
            'type' => 'h2',
            'content' => 'Key Features Demonstrated',
        ],
        [
            'type' => 'ul',
            'items' => [
                'Proper document structure with semantic HTML tags',
                'Alternative text for non-text content',
                'Logical reading order for screen readers',
                'Language specification for text-to-speech',
                'Document metadata (title, author, language, etc.)',
                'Tagged content structure',
            ],
        ],
        [
            'type' => 'h2',
            'content' => 'Structured Table Example',
        ],
        [
            'type' => 'p',
            'content' => 'Tables are properly tagged for screen readers with headers and data cells:',
        ],
        [
            'type' => 'table',
            'headers' => ['Feature', 'Implementation', 'Standard Compliance'],
            'rows' => [
                ['Tagged Structure', 'Semantic HTML elements', 'PDF/UA Required'],
                ['Document Metadata', 'Title, Author, Language', 'PDF/UA Required'],
                ['Reading Order', 'Logical content flow', 'PDF/UA Required'],
                ['Unicode Support', 'UTF-8 encoding', 'PDF/UA Recommended'],
            ],
        ],
        [
            'type' => 'h2',
            'content' => 'Technical Implementation',
        ],
        [
            'type' => 'h3',
            'content' => 'Library Used',
        ],
        [
            'type' => 'p',
            'content' => 'This proof of concept uses TCPDF (https://tcpdf.org/), a PHP library that supports:',
        ],
        [
            'type' => 'ol',
            'items' => [
                'PDF/UA compliance features',
                'Tagged PDF generation',
                'Unicode (UTF-8) text support',
                'Multiple language support',
                'Standards-compliant PDF creation',
            ],
        ],
        [
            'type' => 'h2',
            'content' => 'Benefits of PDF/UA',
        ],
        [
            'type' => 'p',
            'content' => 'Creating accessible PDFs ensures that content is available to all users, regardless of ability. This includes:',
        ],
        [
            'type' => 'ul',
            'items' => [
                'Screen reader compatibility for visually impaired users',
                'Proper document navigation',
                'Support for assistive technologies',
                'Legal compliance with accessibility regulations',
                'Better SEO and content indexing',
            ],
        ],
        [
            'type' => 'h2',
            'content' => 'Conclusion',
        ],
        [
            'type' => 'p',
            'content' => 'This proof of concept demonstrates that it is possible to create PDF/UA compliant documents using PHP and readily available libraries. The combination of TCPDF for PDF generation and proper HTML structure for content organization provides a solid foundation for accessible document creation.',
        ],
    ];

    // Generate HTML from structured content
    $html = $generator->generateStructuredHtml($content);

    // Output directory
    $outputDir = __DIR__ . '/storage';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    $outputPath = $outputDir . '/pdf-ua-example.pdf';

    // Generate the PDF
    echo "Generating PDF with the following configuration:\n";
    echo "- Title: " . $generator->getConfig()['title'] . "\n";
    echo "- Author: " . $generator->getConfig()['author'] . "\n";
    echo "- Language: " . $generator->getConfig()['language'] . "\n";
    echo "- Format: " . $generator->getConfig()['format'] . "\n\n";

    $generator->generate($html, $outputPath);

    echo "✓ PDF generated successfully!\n";
    echo "✓ Output file: {$outputPath}\n\n";
    echo "You can now open this file with a PDF reader or test it with accessibility tools.\n";

} catch (Exception $e) {
    echo "✗ Error generating PDF: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
