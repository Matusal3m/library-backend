<?php
namespace App\Models\DAOs;

use App\Models\Entities\Loan;
use App\Models\Mappers\LoanMapper;
use Database\Database;

class LoanDAO
{
    public function __construct(private Database $db)
    {
        $this->db = $db;
    }

    public function save(Loan $loan): Loan
    {
        $student_id = $loan->getStudent()->getId();
        $book_id    = $loan->getBook()->getId();

        $query =
            'INSERT INTO loans
            (student_id, book_id)
            VALUES
            (:student_id, :book_id)';

        $binds = [
            'student_id' => $student_id,
            'book_id'    => $book_id,
        ];

        $this->db->prepareAndExec($query, $binds);
        $loan->setId($this->db->lastInsertId());
        return $loan;
    }

    public function getById(int $id): ?Loan
    {
        $query = 'SELECT * FROM loans WHERE id = :id';
        $binds = ['id' => $id];

        $loanRow = $this->db->prepareAndExec($query, $binds);

        $loan = LoanMapper::mapRowToLoan($loanRow);
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
            fn($row) => LoanMapper::mapRowToLoan($row),
            $this->getAllRaw()
        );
    }

    public function update(Loan $loan): void
    {
        $started_at  = $loan->getStartedAt();
        $finish_date = $loan->getFinishDate();
        $extended_at = $loan->getExtendedAt();

        $query = 'UPDATE loans SET
            started_at = :started_at
            finish_date = :finish_date
            extended_at = :extended_at
            WHERE id = :id
        ';

        $binds = [
            'started_at'  => $started_at,
            'finish_date' => $finish_date,
            'extended_at' => $extended_at,
        ];

        $this->db->prepareAndExec($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM loans WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExec($query, $binds);
    }
}
