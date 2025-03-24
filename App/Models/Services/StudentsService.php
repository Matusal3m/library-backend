<?php
namespace App\Models\Services;

use App\Models\DAOs\StudentDAO;
use App\Models\Entities\Student;
use App\Models\Mappers\StudentMapper;
use App\Util\Converter;
use Exception;

class StudentsService
{
    public function __construct(
        private StudentDAO $studentDAO,
        private StudentMapper $studentMapper,
    ) {}

    public function createStudent(array $data): Student
    {
        if (! isset($data['name'], $data['enrollment_number'], $data['class_room'])) {
            throw new Exception("Missing name, enrollment_number and/or class_room", 404);
        }

        $student = $this->studentMapper->mapArrayToStudent([
             ...$data,
            'has_active_loan' => false,
        ]);

        return $this->studentDAO->save($student);
    }

    public function getStudent(int $id): Student
    {
        $student = $this->studentDAO->getById($id);

        if (! $student) {
            throw new Exception("Student not found", 404);
        }

        return $student;
    }

    public function getAllStudents(): array
    {
        $students = $this->studentDAO->getAllRaw();
        return Converter::convertKeysToBoolean($students, ['has_active_loan']);
    }

    public function updateStudent($studentId, $data): void
    {
        $student = $this->getStudent($studentId);

        if (isset($data['name'])) {
            $student->updateName($data['name']);
        }

        if (isset($data['enrollment_number'])) {
            $student->updateEnrollmentNumber($data['enrollment_number']);
        }

        if (isset($data['class_room'])) {
            $student->updateClassRoom($data['class_room']);
        }

        $this->studentDAO->update($student);
    }

    public function deleteStudent(int $studentId): void
    {
        $student = $this->studentDAO->getById($studentId);

        if (! $student) {
            throw new Exception("Student not found", 404);
        }

        $this->studentDAO->delete($studentId);
    }
}
