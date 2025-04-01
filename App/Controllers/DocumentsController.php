<?php
namespace App\Controllers;

use App\Models\DAOs\LoanDAO;
use App\Models\Services\DocumentsService;
use App\Models\Services\LoansService;
use Http\Request;
use Http\Response;

class DocumentsController
{
    public function __construct(
        private DocumentsService $documentsService,
        private LoansService $loansService,
        private LoanDAO $loanDAO
    ) {
    }

    public function getLoansPdf(Request $request, Response $response): void
    {
        $loans = $this->loanDAO->getJoinedLoansByClassRoom('AC');
        $response->json($loans);
    }
}
