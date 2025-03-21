<?php
namespace Database;

use App\Util\Files;
use Exception;

class DatabaseInit
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function applyTables(): void
    {
        $tableDir = __DIR__ . '/Tables';

        $queriesToCreateTable = Files::getContentFromDir($tableDir);

        $sucess = $this->db->exec($queriesToCreateTable);

        if (! $sucess) {
            throw new Exception("DATABASE EXCEPTION: Error appling database tables");
        }
    }
}
