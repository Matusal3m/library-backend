<?php
namespace Library\App\Models\Entities;

use DomainException;
use Exception;
use LogicException;

class Author
{
    private int|null $id = null;

    private string $name;

    private array $books;

    public function __construct(string $name, array $books)
    {
        $this->name  = $name;
        $this->books = $books;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $newName): static
    {
        if (empty($newName)) {
            throw new Exception("Author name cannot be empty", 1);
        }

        return $this;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function addBook(Book $newBook): void
    {
        if ($this->bookWasAlreadyAdded($newBook)) {
            throw new DomainException("Book was already added");
        }

        $this->books[] = $newBook;
    }

    private function bookWasAlreadyAdded(Book $newBook): bool
    {
        foreach ($this->books as $book) {
            if ($book->getId() === $newBook->getId()) {
                return true;
            }
        }

        return false;
    }

}
