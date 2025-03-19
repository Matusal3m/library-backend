<?php
namespace Library\App\Models\Entities;

use Exception;
use LogicException;

class Student
{
    private int|null $id;

    private string $name;

    private int $enrollmentNumber;

    private string $classRoom;

    private bool $hasActiveLoan;

    public function __construct(
        string $name,
        int $enrollmentNumber,
        string $classRoom,
        bool $hasActiveLoan
    ) {
        $this->name             = $name;
        $this->enrollmentNumber = $enrollmentNumber;
        $this->classRoom        = $classRoom;
        $this->hasActiveLoan    = $hasActiveLoan;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException("ID already exist and cannot by changed.");
        }

        $this->id = $id;
    }

    public function getId(): int | null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $name): void
    {
        if (empty($name)) {
            throw new Exception("Student name cannot be empty.");
        }

        $this->name = $name;
    }

    public function getEnrollmentNumber(): int
    {
        return $this->enrollmentNumber;
    }

    public function updateEnrollmentNumber(int $enrollmentNumber): void
    {
        $this->enrollmentNumber = $enrollmentNumber;
    }

    public function getClassRoom(): string
    {
        return $this->classRoom;
    }

    public function updateClassRoom(string $classRoom): void
    {
        if (empty($classRoom)) {
            throw new Exception("Class room cannot be empty.");
        }

        $this->classRoom = $classRoom;
    }

    public function setHasActiveLoan(bool $hasActiveLoan): void
    {
        $this->hasActiveLoan = $hasActiveLoan;
    }

    public function getHasActiveLoan(): bool
    {
        return $this->hasActiveLoan;
    }

}
