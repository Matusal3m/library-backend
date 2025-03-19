<?php
namespace Library\App\Models\Entities;

use DomainException;
use LogicException;

class Book
{
    private int|null $id = null;

    private string $title;

    private int $author_id;

    private bool $isAvailable;

    protected string $SeducCode;

    public function __construct(string $title, int $author_id, bool $isAvailable, string $SeducCode)
    {
        $this->validateTitle($title);

        $this->title       = $title;
        $this->author_id   = $author_id;
        $this->isAvailable = $isAvailable;
        $this->SeducCode   = $SeducCode;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException("ID already exist and cannot by changed");
        }

        $this->id = $id;
    }

    public function getId(): int | null
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

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function updateAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
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

    public function getSeducCode(): string
    {
        return $this->SeducCode;
    }

    public function setSeducCode(string $seducCode): void
    {
        $this->SeducCode = $seducCode;
    }
}
