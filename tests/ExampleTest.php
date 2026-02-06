<?php

use Bambamboole\LaravelPdfUa\PdfUaGenerator;

it('can generate structured HTML from content array', function () {
    $generator = new PdfUaGenerator();

    $content = [
        ['type' => 'h1', 'content' => 'Test Heading'],
        ['type' => 'p', 'content' => 'Test paragraph'],
    ];

    $html = $generator->generateStructuredHtml($content);

    expect($html)->toContain('<h1>Test Heading</h1>')
        ->and($html)->toContain('<p>Test paragraph</p>');
});

it('can generate PDF from HTML', function () {
    $generator = new PdfUaGenerator([
        'title' => 'Test PDF',
        'author' => 'Test Author',
    ]);

    $html = '<h1>Test Document</h1><p>This is a test.</p>';

    $pdfContent = $generator->generate($html);

    expect($pdfContent)->toBeString()
        ->and($pdfContent)->toContain('%PDF');
});

it('can configure PDF settings', function () {
    $generator = new PdfUaGenerator();

    $generator->setConfig([
        'title' => 'Custom Title',
        'author' => 'Custom Author',
    ]);

    $config = $generator->getConfig();

    expect($config['title'])->toBe('Custom Title')
        ->and($config['author'])->toBe('Custom Author');
});

it('renders list items correctly', function () {
    $generator = new PdfUaGenerator();

    $content = [
        [
            'type' => 'ul',
            'items' => ['Item 1', 'Item 2', 'Item 3'],
        ],
    ];

    $html = $generator->generateStructuredHtml($content);

    expect($html)->toContain('<ul>')
        ->and($html)->toContain('<li>Item 1</li>')
        ->and($html)->toContain('<li>Item 2</li>')
        ->and($html)->toContain('<li>Item 3</li>')
        ->and($html)->toContain('</ul>');
});

it('renders tables correctly', function () {
    $generator = new PdfUaGenerator();

    $content = [
        [
            'type' => 'table',
            'headers' => ['Column 1', 'Column 2'],
            'rows' => [
                ['Data 1', 'Data 2'],
                ['Data 3', 'Data 4'],
            ],
        ],
    ];

    $html = $generator->generateStructuredHtml($content);

    expect($html)->toContain('<table')
        ->and($html)->toContain('<th>Column 1</th>')
        ->and($html)->toContain('<td>Data 1</td>')
        ->and($html)->toContain('</table>');
});
