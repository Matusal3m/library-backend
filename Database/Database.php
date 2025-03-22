<?php
namespace Database;

use DateTime;
use Exception;
use RuntimeException;
use SQLite3;
use SQLite3Exception;

class Database
{
    private SQLite3 $connection;

    public function __construct()
    {
        $this->connection = new SQLite3(__DIR__ . '/database.db');
    }

    /**
     * Return the database connection
     */

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Prepares an SQL statement, binds values, and executes it.
     *
     * This function creates a prepared statement from the given query, binds all values
     * specified in the $toBind array, and executes the statement. It simplifies the process
     * of using prepared statements with parameter binding.
     *
     * WARNING: Don't use this for INSERT or UPDATE queries, it may cause duplication
     *
     * @param string $query The SQL query with named placeholders (e.g., ':id')
     * @param array $toBind Associative array where keys are parameter names (without including colon)
     *                      and values are the corresponding values to bind
     * @return false|mixed Returns the query value on successful execution, false on failure
     *
     */

    public function prepareAndFetch(string $query, array $toBind): array
    {
        $stmt    = $this->connection->prepare($query);
        $results = [];

        foreach ($toBind as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $rows = $stmt->execute();

        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * Prepares an SQL statement, binds values, and executes it.
     *
     * This function creates a prepared statement from the given query, binds all values
     * specified in the $toBind array, and executes the statement, without returning the result.
     *
     * @param string $query The SQL query with named placeholders (e.g., ':id')
     * @param array $toBind Associative array where keys are parameter names (without including colon)
     *                      and values are the corresponding values to bind
     * @return void
     *
     */

    public function prepareAndExecute(string $query, array $toBind): void
    {
        $stmt = $this->connection->prepare($query);

        foreach ($toBind as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
    }

    /**
     * Returns the row ID of the most recent INSERT into the database
     *
     * @return mixed
     */
    public function lastInsertId(): mixed
    {
        return $this->connection->lastInsertRowID();
    }

    /**
     * Executes an SQL query
     *
     * @return mixed - The query result
     */
    public function query(string $query): array
    {
        $result = [];
        $rows   = $this->connection->query($query);

        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Executes a result-less query against a given database
     * @return bool — TRUE if the query succeeded, FALSE on failure.
     */
    public function exec($query): bool
    {
        return $this->connection->exec($query);
    }

    /**
     * Creates a database backup file in the backup directory
     *
     * Generates an SQLite backup file with timestamped filename in the format:
     * `backup_YYYYMMDD_HHMMSS.db`. Creates backup directory if it doesn't exist.
     *
     * @return void
     *
     * @throws RuntimeException If backup directory creation fails
     * @throws SQLite3Exception If database backup operation fails
     *
     */
    public function createBackup(): void
    {
        $backupDir = $this->prepareBackupDir();
        $fileName  = 'backup_' . (new DateTime())->format('Ymd_His') . '.db';

        try {
            $backup = new SQLite3("$backupDir/$fileName");
            $this->connection->backup($backup);
        } catch (Exception $e) {
            throw new SQLite3Exception("Backup failed: " . $e->getMessage());
        }
    }

    /**
     * Prepares and validates the backup directory
     *
     * Creates the backup directory if it doesn't exist. Ensures proper permissions
     * for directory creation and file writing.
     *
     * @return string Path to validated backup directory
     *
     * @throws RuntimeException If directory creation fails or directory isn't writable
     */
    private function prepareBackupDir(): string
    {
        $backupDir = './backup';

        if (! is_dir($backupDir) && ! mkdir($backupDir, 0755, true)) {
            throw new RuntimeException("Failed to create backup directory: $backupDir");
        }

        if (! is_writable($backupDir)) {
            throw new RuntimeException("Backup directory not writable: $backupDir");
        }

        return $backupDir;
    }

}
