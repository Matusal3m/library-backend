<?php
namespace App\Models\Mappers;

use App\Models\Entities\Book;

class BookMapper
{
    public function mapArrayToBook(array $row): Book
    {
        $book = new Book(
            $row['title'],
            $row['author_id'],
            $row['is_available'],
            $row['seduc_code'],
            $row['genre'],
            $row['quantity'],
        );

        if (isset($row['id'])) {
            $book->setId($row['id']);
        }

        return $book;
    }
}
