<?php
namespace Library\Models\Entities;

use DomainException;
use Exception;
use LogicException;

class Book
{
    private string|null $id = null;

    private string $title;

    private Author $author;

    private bool $isAvailable;

    private Loan|null $loan;

    public function __construct(string $title, Author $author, bool $isAvailable)
    {
        $this->validateTitle($title);

        $this->title       = $title;
        $this->author      = $author;
        $this->isAvailable = $isAvailable;
    }

    public function setId(string $id): void
    {
        if ($this->id !== null) {
            throw new LogicException("ID already exist and cannot by changed");
        }

        $this->id = $id;
    }

    public function getId(): string | null
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function updateTitle(string $newTitle): void
    {
        $this->validateTitle($newTitle);

        $this->title = $newTitle;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setIsAvailable(bool $available): void
    {
        $this->isAvailable = $available;
    }

    public function getIsAvailable(): bool
    {
        return $this->isAvailable;
    }

    private function validateTitle(string $title): void
    {
        if (empty($title)) {
            throw new DomainException("TItle cannot be empty");
        }
    }

    public function getLoan(): Loan
    {
        return $this->loan;
    }

    public function setLoan(Loan $loan): void
    {
        if ($this->loan) {
            throw new Exception("Book already has a Loan");
        }

        $this->loan = $loan;
    }

    public function removeLoan(): void
    {
        if (! $this->loan) {
            throw new Exception("Book dont have a Loan");
        }

        $this->loan = null;
    }
}
