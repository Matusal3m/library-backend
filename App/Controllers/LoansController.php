<?php
namespace App\Controllers;

use App\Models\DAOs\BookDAO;
use App\Models\DAOs\LoanDAO;
use App\Models\DAOs\StudentDAO;
use App\Models\Mappers\LoanMapper;
use Exception;
use Http\Request;
use Http\Response;

class LoansController
{

    public function __construct(
        private LoanMapper $loanMapper,
        private LoanDAO $loanDAO,
        private StudentDAO $studentDAO,
        private BookDAO $bookDAO,
    ) {
        $this->loanMapper = $loanMapper;
        $this->loanDAO    = $loanDAO;
        $this->studentDAO = $studentDAO;
        $this->bookDAO    = $bookDAO;
    }

    public function create(Request $request, Response $response): void
    {
        $data      = $request->body();
        $studentId = $data['student_id'];
        $bookId    = $data['book_id'];

        if (! isset($studentId, $bookId)) {
            $response->json(['error' => 'Missing student_id and/or book_id'], 400);
            return;
        }

        if (
            ! filter_var($studentId, FILTER_VALIDATE_INT) ||
            ! filter_var($bookId, FILTER_VALIDATE_INT)
        ) {
            $response->json(['error' => 'student_id and book_id must be an int'], 400);
            return;
        }

        $student = $this->studentDAO->getById($studentId);
        if (! $student) {
            $response->json(['error' => 'Student not found'], 404);
        }

        $book = $this->bookDAO->getById($bookId);
        if (! $book) {
            $response->json(['error' => 'Book not found'], 404);
        }

        try {
            $loan = $this->loanMapper->mapArrayToLoan($data);

            $savedLoan = $this->loanDAO->save($loan);

            $response->json([
                'id'          => $savedLoan->getId(),
                'student_id'  => $savedLoan->getStudent(),
                'book_id'     => $savedLoan->getBook(),
                'started_at'  => $savedLoan->getStartedAt(),
                'finish_date' => $savedLoan->getFinishDate(),
                'is_active'   => $savedLoan->getIsActive(),
            ]);

        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getById(Request $request, Response $response, array $id): void
    {
        $loanId = $id[0];

        if (! filter_var($loanId, FILTER_VALIDATE_INT)) {
            $response->json(['error' => 'Loan id must be an int'], 400);
            return;
        }

        try {
            $loan = $this->loanDAO->getById($loanId);

            if (! $loan) {
                $response->json(['error' => 'Loan not found'], 404);
                return;
            }

            $response->json([
                'student_id'  => $loan->getStudent(),
                'book_id'     => $loan->getBook(),
                'started_at'  => $loan->getStartedAt(),
                'finish_date' => $loan->getFinishDate(),
                'is_active'   => $loan->getIsActive(),
            ]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAll(Request $request, Response $response): void
    {
        try {
            $data = $this->loanDAO->getAllRaw();

            $response->json($data);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, Response $response, array $id): void
    {
        $loanId = $id[0];

        if (! filter_var($loanId, FILTER_VALIDATE_INT)) {
            $response->json(['error' => 'Loan id must be an int'], 400);
            return;
        }

        try {
            $loan = $this->loanDAO->getById($loanId);
            if (! $loan) {
                $response->json(['error' => 'Loan not found'], 404);
                return;
            }

            $this->loanDAO->delete($loanId);
            $response->json(['status' => 'sucess']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }
}
