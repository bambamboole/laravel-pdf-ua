<?php

namespace Bambamboole\PdfUA\Commands;

use Illuminate\Console\Command;

class PdfUACommand extends Command
{
    public $signature = 'laravel-pdf-ua';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
