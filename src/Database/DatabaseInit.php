<?php
namespace Library\Database;

use Exception;
use Library\Util\Files;
use SQLite3;

class DatabaseInit
{
    private SQLite3 $db;

    public function __construct()
    {
        $db = new Database()->getConnection();

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
