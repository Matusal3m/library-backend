<?php
namespace App\Models\Entities;

use DomainException;
use LogicException;

class Book
{
    private int|null $id = null;

    private string $title;

    private int $author_id;

    private bool $isAvailable;

    private string $SeducCode;

    private string $genre;

    private int $quantity;

    public function __construct(
        string $title,
        int $author_id,
        bool $isAvailable,
        string $SeducCode,
        string $genre,
        int $quantity
    ) {
        $this->validateTitle($title);
        $this->validateGenre($genre);

        $this->title       = $title;
        $this->author_id   = $author_id;
        $this->isAvailable = $isAvailable;
        $this->SeducCode   = $SeducCode;
        $this->genre       = $genre;
        $this->quantity    = $quantity;
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

    public function setTitle(string $newTitle): void
    {
        $this->validateTitle($newTitle);

        $this->title = $newTitle;
    }

    private function validateTitle(string $title): void
    {
        if (empty($title)) {
            throw new DomainException("TItle cannot be empty");
        }
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
        return boolval($this->isAvailable);
    }

    public function getSeducCode(): string
    {
        return $this->SeducCode;
    }

    public function setSeducCode(string $seducCode): void
    {
        $this->SeducCode = $seducCode;
    }

    public function setGenre($genre): void
    {
        $this->validateGenre($genre);
        $this->genre = $genre;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    private function validateGenre($genre): void
    {
        if (empty($genre)) {
            throw new DomainException('Title cannot be empty');
        }
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity > 0) {
            throw new DomainException("Book quantity cannot be negative", 1);
        }

        $this->quantity = $quantity;
    }
}
