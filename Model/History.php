<?php

/**
 * Histories query class
 */

class History
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
     * Insert a new history into the histories table
     * @param array $history consist of (user_id, username, product_id, product_name, quantity, total_price)
     * @return boolean success
     */
    public function insert($history)
    {
        try {

            $query = 'INSERT INTO histories (user_id, username, product_id, product_name, quantity, total_price, date) VALUES (:user_id, :username, :product_id, :product_name, :quantity, :total_price, datetime("now", "localtime"));';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':user_id' => $history['user_id'],
                ':username' => $history['username'],
                ':product_id' => $history['product_id'],
                ':product_name' => $history['product_name'],
                ':quantity'  => $history['quantity'],
                ':total_price' => $history['total_price'],
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * @return array of associativeArray histories
     */
    public function get()
    {
        $stmt = $this->pdo->query('SELECT * FROM histories ORDER BY date ASC;');
        $histories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $histories[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity'  => $row['quantity'],
                'total_price' => $row['total_price'],
                'date'  => $row['date'],
            ];
        }
        return $histories;
    }

    /**
     * @param string|integer $id
     * @return associative array based on condition
     */
    public function whereId($id)
    {
        $query = 'SELECT * FROM histories WHERE id = :id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);

        $histories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $histories[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity'  => $row['quantity'],
                'total_price' => $row['total_price'],
                'date'  => $row['date'],
            ];
        }
        return $histories;
    }

    /**
     * @param string $id
     * @return integer numberofRows deleted
     */
    public function deleteById($id)
    {
        $query = 'DELETE FROM histories WHERE id = :id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * History belongs to User
     * Get history based on user_id
     * @param integer|string $id
     * @return associativeArray $history
     */
    public function whereUserId($userId)
    {
        $query = 'SELECT * FROM histories WHERE user_id = :user_id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':user_id' => $userId
        ]);

        $histories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $histories[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity'  => $row['quantity'],
                'total_price' => $row['total_price'],
                'date'  => $row['date'],
            ];
        }
        return $histories;
    }

    public function whereUserName($username)
    {
        $query = 'SELECT * FROM histories WHERE username = :username;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':username' => $username
        ]);

        $histories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $histories[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity'  => $row['quantity'],
                'total_price' => $row['total_price'],
                'date'  => $row['date'],
            ];
        }
        return $histories;
    }

    /**
     * History belongs to Product
     * Get history based on product_id
     * @param integer|string $id
     * @return associativeArray $history
     */
    public function whereProductId($productId)
    {
        $query = 'SELECT * FROM histories WHERE product_id = :product_id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':product_id' => $productId
        ]);

        $histories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $histories[] = [
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'product_id' => $row['product_id'],
                'product_name' => $row['product_name'],
                'quantity'  => $row['quantity'],
                'total_price' => $row['total_price'],
                'date'  => $row['date'],
            ];
        }
        return $histories;
    }
}
