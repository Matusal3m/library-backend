<?php
namespace Library\App\Models\Mappers;

use Library\App\Models\DAOs\BookDAO;
use Library\App\Models\DAOs\StudentDAO;
use Library\App\Models\Entities\Loan;

class LoanMapper
{
    public function __construct(
        private StudentDAO $studentDAO, private BookDAO $bookDAO) {
        $this->studentDAO = $studentDAO;
        $this->bookDAO    = $bookDAO;
    }

    public function mapRowToLoan(array $row): Loan
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
