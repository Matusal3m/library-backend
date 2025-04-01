<?php
namespace App\Models\Entities;

use App\Util\HTMLBuilder;

class LoansDocument
{
    public function __construct(
        private HTMLBuilder $htmlBuilder
    ) {}

    public function generatePdf(array $content)
    {
    }
}
