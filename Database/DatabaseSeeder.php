<?php
namespace Database;

use Exception;
use Faker\Factory;
use Faker\Generator;

class DatabaseSeeder
{
    private Database $db;
    private Generator $faker;
    private array $config;

    public function __construct(Database $db)
    {
        $this->db    = $db;
        $this->faker = Factory::create('pt_BR');
    }

    public function configure(array $config): self
    {
        $this->config = array_merge([
            'authors'               => 10,
            'books_per_author'      => 3,
            'students'              => 50,
            'active_loans_percent'  => 30,
            'max_books_per_student' => 1,
        ], $config);

        return $this;
    }

    public function seed(): void
    {
        $this->db->exec('BEGIN TRANSACTION');

        try {
            $authorIds  = $this->seedAuthors();
            $bookIds    = $this->seedBooks($authorIds);
            $studentIds = $this->seedStudents();
            $this->seedLoans($studentIds, $bookIds);

            $this->db->exec('COMMIT');
        } catch (Exception $e) {
            $this->db->exec('ROLLBACK');
            throw $e;
        }
    }

    private function seedAuthors(): array
    {
        $authorIds = [];
        for ($i = 0; $i < $this->config['authors']; $i++) {
            $this->db->prepareAndExecute(
                "INSERT INTO authors (name) VALUES (:name)",
                ['name' => $this->faker->unique()->name]
            );
            $authorIds[] = $this->db->lastInsertId();
        }
        return $authorIds;
    }

    private function seedBooks(array $authorIds): array
    {
        $bookIds = [];
        foreach ($authorIds as $authorId) {
            $booksToCreate = $this->config['books_per_author'];

            for ($i = 0; $i < $booksToCreate; $i++) {
                $book = [
                    'title'      => $this->faker->sentence(3),
                    'author_id'  => $authorId,
                    'seduc_code' => 'LIT-' . str_pad($this->faker->unique()->numberBetween(100, 999), 3, '0', STR_PAD_LEFT),
                    'genre'      => $this->faker->randomElement(['Ficção', 'Não-Ficção', 'Poesia', 'Drama', 'Aventura']),
                    'quantity'   => $this->faker->randomDigitNotZero(),
                ];

                $this->db->prepareAndExecute(
                    "INSERT INTO books (title, author_id, seduc_code, genre, quantity)
                    VALUES (:title, :author_id, :seduc_code, :genre, :quantity)",
                    $book
                );
                $bookIds[] = $this->db->lastInsertId();
            }
        }
        return $bookIds;
    }

    private function seedStudents(): array
    {
        $studentIds = [];
        for ($i = 0; $i < $this->config['students']; $i++) {
            $student = [
                'enrollment_number' => $this->faker->unique()->numerify('########'),
                'name'              => $this->faker->name,
                'class_room'        => $this->faker->numerify('#') . $this->faker->randomElement(['A', 'B', 'C']),
            ];

            $this->db->prepareAndExecute(
                "INSERT INTO students (enrollment_number, name, class_room)
                VALUES (:enrollment_number, :name, :class_room)",
                $student
            );
            $studentIds[] = $this->db->lastInsertId();
        }
        return $studentIds;
    }

    private function seedLoans(array $studentIds, array $bookIds): void
    {
        foreach ($studentIds as $studentId) {
            if (! $this->faker->boolean($this->config['active_loans_percent'])) {
                continue;
            }

            $maxLoans = $this->config['max_books_per_student'];
            $books    = $this->faker->randomElements($bookIds, $this->faker->numberBetween(1, $maxLoans));

            foreach ($books as $bookId) {
                $loan = [
                    'student_id'  => $studentId,
                    'book_id'     => $bookId,
                    'started_at'  => $this->faker->dateTimeBetween('-1 month')->format('d-m-Y H:i'),
                    'finish_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('d-m-Y H:i'),
                    'is_active'   => $this->faker->boolean(80),
                ];

                $this->db->prepareAndExecute(
                    "INSERT INTO loans (student_id, book_id, started_at, finish_date, is_active)
                    VALUES (:student_id, :book_id, :started_at, :finish_date, :is_active)",
                    $loan
                );

                if ($loan['is_active']) {
                    $this->db->prepareAndExecute(
                        "UPDATE students SET has_active_loan = TRUE WHERE id = :id",
                        ['id' => $studentId]
                    );
                }
            }

        }
    }
}
