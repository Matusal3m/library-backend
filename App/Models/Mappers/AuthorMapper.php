<?php
namespace App\Models\Mappers;

use App\Models\DAOs\BookDAO;
use App\Models\Entities\Author;

class AuthorMapper
{
    public function __construct(private BookDAO $bookDao)
    {
        $this->bookDao = $bookDao;
    }

    public function mapRowToAuthor(array $row): Author
    {
        $author = new Author($row['name']);

        if (isset($row['id'])) {
            $author->setId($row['id']);
        }

        return $author;
    }
}
