<?php
namespace Library\App\Models\Mappers;

use Library\App\Models\DAOs\BookDAO;
use Library\App\Models\Entities\Author;

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
