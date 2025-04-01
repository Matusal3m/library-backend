<?php
namespace App\Models\Services;

use App\Models\Entities\LoansDocument;

class DocumentsService
{
    public function __construct(private LoansDocument $loansDocument)
    {}

    public function generateLoansPdf(array $content): void
    {
        $this->loansDocument->generatePdf($content);
    }
}
