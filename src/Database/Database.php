<?php
namespace Library\Database;

use DateTime;
use SQLite3;

class Database
{
    private SQLite3 $connection;

    public function __construct()
    {
        $this->connection = new SQLite3('./database.db');
    }

    public function getConnection(): SQLite3
    {
        return $this->connection;
    }

    public function createBackup(): void
    {
        $backupDir = $this->prepareBackupDir();
        $fileName  = 'backup.db' . new DateTime('now');

        $backup = new SQLite3("$backupDir/$fileName");

        $this->connection->backup($backup);
    }

    private function prepareBackupDir(): string
    {
        $backupDir = './backup';

        mkdir($backupDir);

        return $backupDir;
    }

}
