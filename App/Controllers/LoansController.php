<?php
namespace App\Controllers;

use App\Models\Services\LoansService;
use App\Util\Converter;
use App\Validation\IdValidator;
use Exception;
use Http\Request;
use Http\Response;

class LoansController
{

    public function __construct(
        private LoansService $loanService
    ) {
        $this->loanService = $loanService;
    }

    public function create(Request $request, Response $response): void
    {
        $data = $request->body();

        if (! isset($data['student_id'], $data['book_id'])) {
            $response->json(['error' => 'Missing student_id and/or book_id'], 400);
            return;
        }

        try {
            [$studentId, $bookId] = IdValidator::validateMany([
                'studentId' => $data['student_id'],
                'bookId'    => $data['book_id'],
            ]);

            $createdLoan = $this->loanService->createLoan($studentId, $bookId);

            $response->json([
                'id'          => $createdLoan->getId(),
                'student_id'  => $createdLoan->getStudent()->getId(),
                'book_id'     => $createdLoan->getBook()->getId(),
                'started_at'  => $createdLoan->getStartedAt(),
                'extended_at' => $createdLoan->getExtendedAt(),
                'finish_date' => $createdLoan->getFinishDate(),
                'is_active'   => $createdLoan->getIsActive(),
            ]);

        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        try {
            $loanId = IdValidator::validateOne($id[0]);
            $loan   = $this->loanService->getLoan($loanId);

            $response->json([
                'student_id'  => $loan->getStudent()->getId(),
                'book_id'     => $loan->getBook()->getId(),
                'started_at'  => $loan->getStartedAt(),
                'extended_at' => $loan->getExtendedAt(),
                'finish_date' => $loan->getFinishDate(),
                'is_active'   => $loan->getIsActive(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $data = $this->loanService->getAllLoans();

            $data = Converter::convertKeysToBoolean($data, ['is_active']);

            $response->json($data);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        try {
            $loanId = IdValidator::validateOne($id[0]);
            $this->loanService->deleteLoan($loanId);

            $response->json(['status' => 'sucess']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function desactive(Request $request, Response $response): void
    {
        $data = $request->body();

        if (! isset($data['loan_id'])) {
            $response->json(['error' => 'loan_id is required'], 404);
            return;
        }

        try {
            $loanId = IdValidator::validateOne($request['loan_id']);
            $this->loanService->desactiveLoan($loanId);

            $response->json(['status' => 'sucess']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }

    public function extend(Request $request, Response $response): void
    {
        $data = $request->body();

        if (! isset($data['loan_id'])) {
            $response->json(['error' => 'loan_id is required'], 404);
            return;
        }

        try {
            $loanId = IdValidator::validateOne($data['loan_id']);
            $loan   = $this->loanService->extendLoan($loanId);

            $response->json([
                'student_id'  => $loan->getStudent()->getId(),
                'book_id'     => $loan->getBook()->getId(),
                'started_at'  => $loan->getStartedAt(),
                'extended_at' => $loan->getExtendedAt(),
                'finish_date' => $loan->getFinishDate(),
                'is_active'   => $loan->getIsActive(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], $e->getCode() ?? 500);
        }
    }
}
