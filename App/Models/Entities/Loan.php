<?php
namespace App\Models\Entities;

use DateTime;
use Exception;
use LogicException;

class Loan
{
    private int|null $id;

    private Student $student;

    private Book $book;

    private DateTime $startedAt;

    private DateTime $finishDate;

    private DateTime|null $extendedAt;

    private bool $isActive;

    public function __construct(Student $student, Book $book, bool $isActive)
    {
        if (! $book->getIsAvailable()) {
            throw new Exception("Book cannot be borrowed");
        }

        if ($student->getHasActiveLoan()) {
            throw new Exception("Student has already one book");
        }

        $this->book       = $book;
        $this->startedAt  = date_create('now');
        $this->finishDate = date_create('now')->add(date_interval_create_from_date_string('15 days'));
        $this->isActive   = $isActive;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException("ID already exist and cannot by changed");
        }

        $this->id = $id;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
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

    public function getStartedAt(): DateTime
    {
        return $this->startedAt;
    }

    public function getFinishDate(): DateTime
    {
        return $this->finishDate;
    }

    public function getExtendedAt(): DateTime
    {
        return $this->extendedAt;
    }

    public function extendLoan(): void
    {
        $this->extendedAt = date_create('now');

        $prevFinishDate = $this->finishDate;

        $newFinishDate = date_add(
            $prevFinishDate,
            date_interval_create_from_date_string('15 days')
        );

        $this->finishDate = $newFinishDate;
    }

}
