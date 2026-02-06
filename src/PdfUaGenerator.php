<?php

namespace Bambamboole\LaravelPdfUa;

use TCPDF;

class PdfUaGenerator
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
    }

    protected function getDefaultConfig(): array
    {
        return [
            'title' => 'PDF/UA Document',
            'author' => 'Laravel PDF/UA',
            'subject' => 'Accessible PDF Document',
            'keywords' => 'PDF, UA, Accessibility',
            'creator' => 'Laravel PDF/UA Package',
            'language' => 'en-US',
            'pdf_version' => '1.7',
            'orientation' => 'P',
            'unit' => 'mm',
            'format' => 'A4',
        ];
    }

    public function generate(string $html, ?string $outputPath = null): string|bool
    {
        // Create new PDF document with PDF/UA compliance
        $pdf = new TCPDF(
            $this->config['orientation'],
            $this->config['unit'],
            $this->config['format'],
            true,
            'UTF-8',
            false
        );

        // Set document information for PDF/UA
        $pdf->SetCreator($this->config['creator']);
        $pdf->SetAuthor($this->config['author']);
        $pdf->SetTitle($this->config['title']);
        $pdf->SetSubject($this->config['subject']);
        $pdf->SetKeywords($this->config['keywords']);

        // Set PDF version for UA compliance
        $pdf->setPDFVersion($this->config['pdf_version']);

        // Enable tagged PDF (required for PDF/UA)
        $pdf->setRTL(false);

        // Set language (required for PDF/UA)
        $pdf->setLanguageArray([
            'a_meta_charset' => 'UTF-8',
            'a_meta_dir' => 'ltr',
            'a_meta_language' => $this->config['language'],
            'w_page' => 'page',
        ]);

        // Remove default header/footer for cleaner output
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        // Add a page
        $pdf->AddPage();

        // Set font for accessibility (use standard fonts that support Unicode)
        $pdf->SetFont('helvetica', '', 12);

        // Write HTML content with structure tags
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output the PDF
        if ($outputPath) {
            return $pdf->Output($outputPath, 'F');
        }

        return $pdf->Output('document.pdf', 'S');
    }

    public function generateStructuredHtml(array $content): string
    {
        $html = '<html><body>';

        foreach ($content as $element) {
            $html .= $this->renderElement($element);
        }

        $html .= '</body></html>';

        return $html;
    }

    protected function renderElement(array $element): string
    {
        $type = $element['type'] ?? 'p';
        $content = $element['content'] ?? '';
        $attributes = $element['attributes'] ?? [];

        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
        }

        return match ($type) {
            'h1' => sprintf('<h1%s>%s</h1>', $attrString, htmlspecialchars($content)),
            'h2' => sprintf('<h2%s>%s</h2>', $attrString, htmlspecialchars($content)),
            'h3' => sprintf('<h3%s>%s</h3>', $attrString, htmlspecialchars($content)),
            'p' => sprintf('<p%s>%s</p>', $attrString, htmlspecialchars($content)),
            'ul' => sprintf('<ul%s>%s</ul>', $attrString, $this->renderListItems($element['items'] ?? [])),
            'ol' => sprintf('<ol%s>%s</ol>', $attrString, $this->renderListItems($element['items'] ?? [])),
            'table' => $this->renderTable($element),
            default => sprintf('<p%s>%s</p>', $attrString, htmlspecialchars($content)),
        };
    }

    protected function renderListItems(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            $html .= sprintf('<li>%s</li>', htmlspecialchars($item));
        }

        return $html;
    }

    protected function renderTable(array $element): string
    {
        $html = '<table border="1" cellpadding="4">';

        if (isset($element['headers'])) {
            $html .= '<thead><tr>';
            foreach ($element['headers'] as $header) {
                $html .= sprintf('<th>%s</th>', htmlspecialchars($header));
            }
            $html .= '</tr></thead>';
        }

        if (isset($element['rows'])) {
            $html .= '<tbody>';
            foreach ($element['rows'] as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= sprintf('<td>%s</td>', htmlspecialchars($cell));
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        $html .= '</table>';

        return $html;
    }

    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
