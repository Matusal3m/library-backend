<?php
namespace App\Models\Mappers;

use App\Models\DAOs\BookDAO;
use App\Models\DAOs\StudentDAO;
use App\Models\Entities\Loan;

class LoanMapper
{
    public function __construct(
        private StudentDAO $studentDAO, private BookDAO $bookDAO) {
        $this->studentDAO = $studentDAO;
        $this->bookDAO    = $bookDAO;
    }

    public function mapArrayToLoan(array $row): Loan
    {
        $student = $this->studentDAO->getById($row['student_id']);
        $book    = $this->bookDAO->getById($row['book_id']);

        $loan = new Loan($student, $book);

        if (isset($row['id'])) {
            $loan->setId($row['id']);
        }

        return $loan;
    }

}
