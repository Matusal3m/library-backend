<?php
namespace Library\App\Models\Repositories\Interfaces;

use Library\Models\Entities\Student;

interface IStudentRepository
{
    public function save(Student $student): Student;
    public function findAll(): array;
    public function findById(string $id): Student;
    public function update(Student $student): Student;
    public function delete(string $id);
}
