<?php
namespace Library\App\Models\DAOs;

use Library\App\Models\Entities\Student;
use Library\App\Models\Mappers\StudentMapper;
use Library\Database\Database;

class StudentDAO
{
    public function __construct(private Database $db)
    {
        $this->db = $db;
    }

    public function save(Student $student): Student
    {
        $name              = $student->getName();
        $class_room        = $student->getClassRoom();
        $enrollment_number = $student->getEnrollmentNumber();

        $query =
            'INSERT INTO students
            (name, enrollment_number, class_room)
            VALUES
            (:name, :class_room, :enrollment_number)';

        $binds = [
            "name"              => $name,
            "class_room"        => $class_room,
            "enrollment_number" => $enrollment_number,
        ];

        $this->db->prepareAndExec($query, $binds);
        $student->setId($this->db->lastInsertId());
        return $student;
    }

    public function getById(int $id): ?Student
    {
        $query = 'SELECT * FROM students WHERE id = :id';
        $binds = ['id' => $id];

        $studentRow = $this->db->prepareAndExec($query, $binds);

        $student = StudentMapper::mapRowToStudent($studentRow);
        return $student;
    }

    public function getByLoanId(int $loan_id): ?Student
    {
        $query = 'SELECT * FROM students WHERE loan_id = :loan_id';
        $binds = ['loan_id' => $loan_id];

        $studentRow = $this->db->prepareAndExec($query, $binds);

        $student = StudentMapper::mapRowToStudent($studentRow);
        return $student;
    }

    public function getAllRaw(): mixed
    {
        $query = 'SELECT * FROM students';

        $booksRows = $this->db->query($query);

        return $booksRows;
    }

    public function getAllMapped(): mixed
    {
        return array_map(
            fn($row) => StudentMapper::mapRowToStudent($row),
            $this->getAllRaw()
        );
    }

    public function update(Student $student): Student
    {
        $id                = $student->getId();
        $name              = $student->getName();
        $class_room        = $student->getClassRoom();
        $enrollment_number = $student->getEnrollmentNumber();
        $loan_id           = $student->getLoan()->getId();

        $query = 'UPDATE students SET
            name = :name,
            enrollment_number = :enrollment_number,
            class_room = :class_room,
            loan_id = :loan_id
            WHERE id = :id';

        $binds = [
            "id"                => $id,
            "name"              => $name,
            "class_room"        => $class_room,
            "enrollment_number" => $enrollment_number,
            "loan_id"           => $loan_id ?? null,
        ];

        return $this->db->prepareAndExec($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM students WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExec($query, $binds);
    }
}
