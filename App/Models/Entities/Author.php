<?php
namespace App\Models\Entities;

use Exception;
use LogicException;

class Author
{
    private int|null $id = null;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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

}
