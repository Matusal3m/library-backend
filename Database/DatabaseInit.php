<?php
namespace Library\Database;

use Exception;
use Library\App\Util\Files;

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
