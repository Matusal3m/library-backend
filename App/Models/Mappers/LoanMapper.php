<?php
namespace App\Models\Mappers;

use App\Models\DAOs\BookDAO;
use App\Models\DAOs\StudentDAO;
use App\Models\Entities\Loan;
use App\Util\DateHandler;

class LoanMapper
{
    public function __construct(
        private StudentDAO $studentDAO, private BookDAO $bookDAO) {
        $this->studentDAO = $studentDAO;
        $this->bookDAO    = $bookDAO;
    }

    public function mapArrayToLoan(array $row): Loan
    {
        $student    = $this->studentDAO->getById($row['student_id']);
        $book       = $this->bookDAO->getById($row['book_id']);
        $isActive   = $row['is_active'];
        $startedAt  = $row['started_at'];
        $extendedAt = $row['extended_at'];
        $finishDate = $row['finish_date'];
        $returnedAt = $row['returned_at'];

        $loan = Loan::fromDatabase(
            $student,
            $book,
            $isActive,
            $startedAt,
            $extendedAt,
            $finishDate,
            $returnedAt
        );

        if (isset($row['id'])) {
            $loan->setId($row['id']);
        }

        return $loan;
    }

    public function mapLoanToArray(Loan $loan): array
    {
        return [
            'id'               => $loan->getId(),
            'student_id'       => $loan->getStudent()->getId(),
            'book_id'          => $loan->getBook()->getId(),
            'started_at'       => $loan->getStartedAt(),
            'extended_at'      => $loan->getExtendedAt(),
            'finish_date'      => $loan->getFinishDate(),
            'is_active'        => $loan->getIsActive(),
            'returned_at'      => $loan->getReturnedAt(),
            'is_late'          => $loan->getIsLate(),
            'returned_on_time' => $loan->getReturnedOnTime(),
        ];
    }

    public function mapLoanToReadableArray(Loan $loan): array
    {
        $extended_at = $loan->getExtendedAt() ? DateHandler::timestampToReadable($loan->getExtendedAt()) : null;
        $returned_at = $loan->getReturnedAt() ? DateHandler::timestampToReadable($loan->getReturnedAt()) : null;

        return [
            'id'               => $loan->getId(),
            'student_id'       => $loan->getStudent()->getId(),
            'book_id'          => $loan->getBook()->getId(),
            'started_at'       => DateHandler::timestampToReadable($loan->getStartedAt()),
            'extended_at'      => $extended_at,
            'finish_date'      => DateHandler::timestampToReadable($loan->getFinishDate()),
            'returned_at'      => $returned_at,
            'is_active'        => $loan->getIsActive(),
            'is_late'          => $loan->getIsLate(),
            'returned_on_time' => $loan->getReturnedOnTime(),
        ];
    }
}
