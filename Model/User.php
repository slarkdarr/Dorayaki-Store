<?php

/**
 * Users query class
 */

class User
{
    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * Initialize the object with a specified PDO object
     * @param \PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insert a new user into the users table
     * @param array $user consist of (email, username, password, role)
     * @return boolean success
     */
    public function insert($user)
    {
        try {
            $query = 'INSERT INTO users (email, username, password, role) VALUES (:email, :username, :password, :role);';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':email' => $user['email'],
                ':username' => $user['username'],
                ':password' => password_hash($user['password'], PASSWORD_DEFAULT),
                ':role'  => $user['role']
            ]);

            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * @return associativeArray users
     */
    public function get()
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = [
                'id' => $row['id'],
                'email' => $row['email'],
                'username' => $row['username'],
                'password' => $row['password'],
                'role' => $row['role']
            ];
        }
        return $users;
    }

    /**
     * @param string|integer $id
     * @return associative array based on condition
     */
    public function whereId($id)
    {
        $query = 'SELECT * FROM users WHERE id = :id LIMIT 1;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);

        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = [
                'id' => $row['id'],
                'email' => $row['email'],
                'username' => $row['username'],
                'password' => $row['password'],
                'role' => $row['role']
            ];
        }

        return $users;
    }

    /**
     * @param string $username
     * @return associative array based on condition
     */
    public function whereUsername($username)
    {
        $query = 'SELECT * FROM users WHERE username = :username LIMIT 1;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':username' => $username
        ]);

        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = [
                'id' => $row['id'],
                'email' => $row['email'],
                'username' => $row['username'],
                'password' => $row['password'],
                'role' => $row['role']
            ];
        }
        return $users;
    }
}
