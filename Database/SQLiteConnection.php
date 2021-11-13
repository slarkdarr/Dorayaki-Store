<?php

/**
 * SQLite connnection
 */
class SQLiteConnection
{
    /**
     * PDO instance
     * @var type 
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @param string $databasePath path relative to database
     * @return \PDO
     */
    public function connect($databasePath)
    {
        if ($this->pdo == null) {
            try {
                $this->pdo = new \PDO("sqlite:" . $databasePath);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $this->pdo;
    }
}
