<?php

namespace Bambamboole\LaravelPdfUa\Commands;

use Bambamboole\LaravelPdfUa\PdfUaGenerator;
use Illuminate\Console\Command;

class GeneratePdfUaCommand extends Command
{
    public $signature = 'pdf-ua:generate {output? : Output file path}';

    public $description = 'Generate a sample PDF/UA compliant document';

    public function handle(): int
    {
        $this->info('Generating PDF/UA document...');

        $generator = new PdfUaGenerator([
            'title' => 'Sample PDF/UA Document',
            'author' => 'Laravel PDF/UA Package',
            'subject' => 'Demonstration of PDF/UA Accessibility Features',
            'keywords' => 'PDF, Universal Accessibility, Structured Content, PHP',
            'language' => 'en-US',
        ]);

        // Create structured content
        $content = [
            [
                'type' => 'h1',
                'content' => 'PDF/UA Proof of Concept',
            ],
            [
                'type' => 'p',
                'content' => 'This is a demonstration of PDF/UA (Universal Accessibility) document generation using PHP and TCPDF library.',
            ],
            [
                'type' => 'h2',
                'content' => 'What is PDF/UA?',
            ],
            [
                'type' => 'p',
                'content' => 'PDF/UA (ISO 14289-1) is an ISO standard for creating accessible PDF documents that can be read by assistive technologies.',
            ],
            [
                'type' => 'h2',
                'content' => 'Key Features',
            ],
            [
                'type' => 'ul',
                'items' => [
                    'Proper document structure with semantic tags',
                    'Alternative text for images',
                    'Logical reading order',
                    'Language specification',
                    'Document metadata',
                ],
            ],
            [
                'type' => 'h2',
                'content' => 'Structured Content Example',
            ],
            [
                'type' => 'p',
                'content' => 'Tables are properly tagged for screen readers:',
            ],
            [
                'type' => 'table',
                'headers' => ['Feature', 'Status', 'Description'],
                'rows' => [
                    ['Tagged Structure', 'Implemented', 'Document uses semantic HTML tags'],
                    ['Metadata', 'Implemented', 'Document includes title, author, and language'],
                    ['Reading Order', 'Implemented', 'Content follows logical sequence'],
                ],
            ],
            [
                'type' => 'h2',
                'content' => 'Conclusion',
            ],
            [
                'type' => 'p',
                'content' => 'This proof of concept demonstrates how to create accessible PDF documents using PHP and the TCPDF library with proper structure and tagging.',
            ],
        ];

        $html = $generator->generateStructuredHtml($content);

        $outputPath = $this->argument('output') ?? storage_path('app/sample-pdf-ua.pdf');

        try {
            $generator->generate($html, $outputPath);
            $this->info("PDF/UA document generated successfully: {$outputPath}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to generate PDF: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
