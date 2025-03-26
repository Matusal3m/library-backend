<?php
namespace App\Models\Services;

use App\Models\DAOs\BookDAO;
use App\Models\DAOs\LoanDAO;
use App\Models\DAOs\StudentDAO;
use App\Models\Entities\Loan;
use App\Models\Mappers\LoanMapper;
use Exception;

class LoansService
{
    public function __construct(
        private LoanDAO $loanDAO,
        private LoanMapper $loanMapper,
        private StudentDAO $studentDAO,
        private BookDAO $bookDAO,
    ) {}

    public function createLoan(int $studentId, int $bookId)
    {
        $student = $this->studentDAO->getById($studentId);
        if (! $student) {
            throw new Exception("Student not found", 404);
        }

        $book = $this->bookDAO->getById($bookId);
        if (! $book) {
            throw new Exception("Book not found", 404);
        }

        $loan = new Loan($student, $book);

        $loan->active();
        $student->setHasActiveLoan(true);
        $book->setIsAvailable(false);

        $savedLoan = $this->loanDAO->save($loan);
        $this->studentDAO->update($student);
        $this->bookDAO->update($book);

        return $savedLoan;
    }

    public function getLoan(int $id): Loan
    {
        $loan = $this->loanDAO->getById($id);

        if (! $loan) {
            throw new Exception("Loan not found", 404);
        }

        return $loan;
    }

    public function getAllLoans(): array
    {
        $loans = $this->loanDAO->getAllMapped();
        return array_map(
            fn($loan) => $this->loanMapper->mapLoanToReadableArray($loan),
            $loans
        );
    }

    public function deleteLoan(int $id)
    {
        $loan = $this->loanDAO->getById($id);

        if (! $loan) {
            throw new Exception("Loan not found", 404);
        }

        $this->loanDAO->delete($id);
    }

    public function desactiveLoan(int $id): void
    {
        $loan = $this->loanDAO->getById($id);

        if (! $loan) {
            throw new Exception("Loan not found", 404);
        }

        $book    = $loan->getBook();
        $student = $loan->getStudent();

        $loan->desactive();
        $student->setHasActiveLoan(false);
        $book->setIsAvailable(true);

        $this->loanDAO->update($loan);
        $this->studentDAO->update($student);
        $this->bookDAO->update($book);
    }

    public function extendLoan(int $id): Loan
    {
        $loan = $this->loanDAO->getById($id);

        $loan->extend();

        $this->loanDAO->update($loan);
        return $loan;
    }

}
