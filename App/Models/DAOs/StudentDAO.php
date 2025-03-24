<?php
namespace App\Models\DAOs;

use App\Models\Entities\Student;
use App\Models\Mappers\StudentMapper;
use Database\Database;

class StudentDAO
{
    public function __construct(
        private Database $db,
        private StudentMapper $studentMapper
    ) {}

    public function save(Student $student): Student
    {
        $name              = $student->getName();
        $class_room        = $student->getClassRoom();
        $enrollment_number = $student->getEnrollmentNumber();

        $query =
            'INSERT INTO students
            (name, enrollment_number, class_room)
            VALUES
            (:name, :enrollment_number, :class_room)';

        $binds = [
            "name"              => $name,
            "class_room"        => $class_room,
            "enrollment_number" => $enrollment_number,
        ];

        $this->db->prepareAndExecute($query, $binds);
        $student->setId($this->db->lastInsertId());
        return $student;
    }

    public function getById(int $id): ?Student
    {
        $query = 'SELECT * FROM students WHERE id = :id';
        $binds = ['id' => $id];

        $studentRow = $this->db->prepareAndFetch($query, $binds);

        if (! $studentRow) {
            return null;
        }

        $student = $this->studentMapper->mapArrayToStudent($studentRow[0]);
        return $student;
    }

    public function getAllRaw(): array
    {
        $query = 'SELECT * FROM students';

        $booksRows = $this->db->query($query);

        return $booksRows;
    }

    public function getAllMapped(): mixed
    {
        return array_map(
            fn($row) => $this->studentMapper->mapArrayToStudent($row),
            $this->getAllRaw()
        );
    }

    public function update(Student $student): void
    {
        $id                = $student->getId();
        $name              = $student->getName();
        $class_room        = $student->getClassRoom();
        $enrollment_number = $student->getEnrollmentNumber();
        $has_active_loan   = $student->getHasActiveLoan();

        $query = 'UPDATE students SET
            name = :name,
            enrollment_number = :enrollment_number,
            class_room = :class_room,
            has_active_loan = :has_active_loan
            WHERE id = :id';

        $binds = [
            "id"                => $id,
            "name"              => $name,
            "class_room"        => $class_room,
            "enrollment_number" => $enrollment_number,
            "has_active_loan"   => $has_active_loan,
        ];

        $this->db->prepareAndExecute($query, $binds);
    }

    public function delete(int $id): void
    {
        $query = 'DELETE FROM students WHERE id = :id';
        $binds = ['id' => $id];

        $this->db->prepareAndExecute($query, $binds);
    }
}
