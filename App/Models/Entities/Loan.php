<?php
namespace App\Models\Entities;

use App\Util\DateHandler;
use Exception;
use LogicException;

class Loan
{
    private int|null $id = null;

    private Student $student;

    private Book $book;

    private string $startedAt;

    private string $finishDate;

    private string|null $extendedAt = null;

    private string|null $returnedAt = null;

    private bool|null $isLate = null;

    private bool|null $returnedOnTime = null;

    private bool $isActive;

    public function __construct(Student $student, Book $book)
    {
        $this->book       = $book;
        $this->student    = $student;
        $this->startedAt  = DateHandler::dateNow();
        $this->finishDate = DateHandler::daysFromNow(15);
        $this->isActive   = $isActive ?? false;
    }

    public static function fromDatabase(
        Student $student,
        Book $book,
        bool $isActive,
        string $startedAt,
        string | null $extendedAt,
        string $finishDate
    ) {
        $loan             = new self($student, $book);
        $loan->isActive   = $isActive;
        $loan->startedAt  = $startedAt;
        $loan->extendedAt = $extendedAt;
        $loan->finishDate = $finishDate;
        return $loan;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException("ID already exists and cannot by changed");
        }

        $this->id = $id;
    }

    public function active(): void
    {
        if ($this->isActive) {
            throw new Exception("Loan is already active");
        }

        if (! $this->book->getIsAvailable() && $this->student->getHasActiveLoan()) {
            throw new Exception("Book cannot be borrowed and Student already has one book");
        }

        if (! $this->book->getIsAvailable()) {
            throw new Exception("Book cannot be borrowed");
        }

        if ($this->student->getHasActiveLoan()) {
            throw new Exception("Student already has one book");
        }

        $this->isActive = true;
    }

    public function desactive(): void
    {
        if (! $this->isActive) {
            throw new Exception("Loan is already desactived");
        }

        $this->isActive = false;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getId(): int | null
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getStartedAt(): string
    {
        return $this->startedAt;
    }

    public function getFinishDate(): string
    {
        return $this->finishDate;
    }

    public function getExtendedAt(): string | null
    {
        return $this->extendedAt;
    }

    public function extend(): void
    {
        $this->extendedAt = DateHandler::dateNow();

        $this->finishDate = DateHandler::daysFromNow(15);
    }

    public function getReturnedAtAt(): string | null
    {
        return $this->returnedAt;
    }

    public function getReturnedOnTime(): bool | null
    {
        return $this->returnedOnTime;
    }

    public function getIsLate(): bool | null
    {
        return $this->isLate;
    }

    private function checkIfIsLate($finishDate, $isActive): bool | null
    {
        if (! $isActive) {
            return null;
        }

        $now = DateHandler::dateNow();
        return DateHandler::isGreater($now, $finishDate);
    }

    private function checkIfWasReturnedOnTime($returnedAt, $finishDate): bool | null
    {
        if (! $returnedAt) {
            return null;
        }

        return DateHandler::isGreater($finishDate, $returnedAt);
    }
}
