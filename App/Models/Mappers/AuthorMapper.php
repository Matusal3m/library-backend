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
        $books = $this->bookDao->getAllMappedByAuthorId($row['id']);

        $author = new Author($row['name'], $books);

        $author->setId($row['id']);
        return $author;
    }
}
