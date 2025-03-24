<?php
namespace App\Validation;

use Exception;

class IdValidator
{
    public static function validateOne(mixed $id): int
    {
        if (! filter_var($id, FILTER_VALIDATE_INT)) {
            throw new Exception("Id must be an int", 404);
        }

        return $id;
    }

    public static function validateMany(array $ids): array
    {
        foreach ($ids as $key => $id) {
            if (! filter_var($id, FILTER_VALIDATE_INT)) {
                throw new Exception("$key must be an int", 404);
            }
        }

        return array_values($ids);
    }
}
