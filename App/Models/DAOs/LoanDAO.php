<?php
namespace App\Models\DAOs;

use App\Models\Entities\Loan;
use App\Models\Mappers\LoanMapper;
use Database\Database;

class LoanDAO
{
    public function __construct(
        private Database $db,
        private LoanMapper $loanMapper
    ) {}

    public function save(Loan $loan): Loan
    {
        $student_id  = $loan->getStudent()->getId();
        $book_id     = $loan->getBook()->getId();
        $started_at  = $loan->getStartedAt();
        $finish_date = $loan->getFinishDate();
        $extended_at = $loan->getExtendedAt();

        $query =
            'INSERT INTO loans
            (student_id, book_id, is_active, started_at, finish_date, extended_at)
            VALUES
            (:student_id, :book_id, :is_active, :started_at, :finish_date, :extended_at)';

        $binds = [
            'student_id'  => $student_id,
            'book_id'     => $book_id,
            'is_active'   => $loan->getIsActive(),
            'started_at'  => $started_at,
            'finish_date' => $finish_date,
            'extended_at' => $extended_at,
        ];

        $this->db->prepareAndExecute($query, $binds);
        $loan->setId($this->db->lastInsertId());
        return $loan;
    }

    public function getById(int $id): ?Loan
    {
        $query = 'SELECT * FROM loans WHERE id = :id';
        $binds = ['id' => $id];

        $loanRow = $this->db->prepareAndFetch($query, $binds);

        if (! $loanRow) {
            return null;
        }

        $loan = $this->loanMapper->mapArrayToLoan($loanRow[0]);

        return $loan;
    }

    public function getAllRaw(): array
    {
        $query = 'SELECT * FROM loans';

        $loansRows = $this->db->query($query);

        return $loansRows;
    }

    public function getAllMapped(): array
    {
        return array_map(
            fn($row) => $this->loanMapper->mapArrayToLoan($row),
            $this->getAllRaw()
        );
    }

    public function getJoinedLoansByClassRoom(
        string $classRoom,
        string $order = 'ASC',
        string $referenceToOrder = 'loans.started_at'
    ): array {
        $query = "SELECT
            students.name,
            books.title,
            students.class_room,
            loans.started_at,
            loans.extended_at,
            loans.finish_date,
            loans.returned_at
        FROM loans
        INNER JOIN students ON loans.student_id = students.id
        INNER JOIN books ON loans.book_id = books.id $order $referenceToOrder
        WHERE loans.class_room = $classRoom";

        $result = $this->db->query($query);

        return $result;
    }

    public function update(Loan $loan): void
    {
        $id          = $loan->getId();
        $started_at  = $loan->getStartedAt();
        $finish_date = $loan->getFinishDate();
        $extended_at = $loan->getExtendedAt();
        $is_active   = $loan->getIsActive();

        $query = 'UPDATE loans SET
            started_at = :started_at,
            finish_date = :finish_date,
            extended_at = :extended_at,
            is_active = :is_active
            WHERE id = :id
        ';

        $binds = [
            'id'          => $id,
            'started_at'  => $started_at,
            'finish_date' => $finish_date,
            'extended_at' => $extended_at,
            'is_active'   => $is_active,
        ];

        $this->db->prepareAndExecute($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM loans WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExecute($query, $binds);
    }
}
