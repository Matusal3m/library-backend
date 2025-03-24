<?php
namespace App\Models\Mappers;

use App\Models\Entities\Student;

class StudentMapper
{
    public function mapArrayToStudent(array $row): Student
    {
        $student = new Student(
            $row['name'],
            $row['enrollment_number'],
            $row['class_room'],
            boolval($row['has_active_loan'])
        );

        if (isset($row['id'])) {
            $student->setId($row['id']);
        }

        return $student;
    }
}
