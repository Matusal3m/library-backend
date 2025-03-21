<?php
namespace App\Models\Mappers;

use App\Models\DAOs\AuthorDAO;
use App\Models\Entities\Book;

class BookMapper
{
    public function __construct(
        private AuthorDAO $authorDAO,
    ) {
        $this->authorDAO = $authorDAO;
    }

    public function mapRowToBook(array $row): Book
    {
        $book = new Book(
            $row['title'],
            $row['author_id'],
            $row['is_available'],
            $row['seduc_code'],
        );

        if (isset($row['id'])) {
            $book->setId($row['id']);
        }

        return $book;
    }
}
