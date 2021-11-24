<?php

/**
 * Products query class
 */

class Product
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
     * Insert a new product into the products table
     * @param array $product consist of (name, description, price, stock, image)
     * @return boolean success
     */
    public function insert($product)
    {
        try {
            $query = 'INSERT INTO products (name, description, price, stock, image, sold) VALUES (:name, :description, :price, :stock, :image, 0);';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':name' => $product['name'],
                ':description' => $product['description'],
                ':price' => $product['price'],
                ':stock'  => $product['stock'],
                ':image'  => $product['image'],
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * @return associativeArray Products
     */
    public function get()
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY sold DESC;');
        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'stock'  => $row['stock'],
                'image'  => $row['image'],
                'sold'  => $row['sold'],
            ];
        }
        return $products;
    }

    /**
     * @param integer|string $pageFirstResult
     * @param integer|string $resultPerPage
     * @return associativeArray Products with paginated
     */
    public function getPaginated($pageFirstResult, $resultPerPage)
    {
        $stmt = $this->pdo->query('SELECT * FROM products ORDER BY sold DESC LIMIT ' . $pageFirstResult . ',' . $resultPerPage . ';');
        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'stock'  => $row['stock'],
                'image'  => $row['image'],
                'sold'  => $row['sold'],
            ];
        }
        return $products;
    }

    /**
     * @param integer|string $pageFirstResult
     * @param integer|string $resultPerPage
     * @param string $search
     * @return associativeArray Products with paginated
     */
    public function getPaginatedSearch($pageFirstResult, $resultPerPage, $search)
    {
        $search = "%" . $search . '%';
        $query = "SELECT * FROM products WHERE name LIKE :search LIMIT :pageFirstResult , :resultPerPage ;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'pageFirstResult' => $pageFirstResult,
            'resultPerPage' => $resultPerPage,
            'search'    => $search
        ]);

        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'stock'  => $row['stock'],
                'image'  => $row['image'],
                'sold'  => $row['sold'],
            ];
        }
        return $products;
    }

    /**
     * @param string $search
     * @return integer $numberOfRow by search
     */
    public function countSearch($search)
    {
        $search = "%" . $search . '%';
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM products WHERE name LIKE :search;");
        $stmt->execute([
            'search' => $search
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    /**
     * @param string|integer $id
     * @return associative array based on condition
     */
    public function whereId($id)
    {
        $query = 'SELECT * FROM products WHERE id = :id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);

        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'stock'  => $row['stock'],
                'image'  => $row['image'],
                'sold'  => $row['sold'],
            ];
        }
        return $products;
    }

    /**
     * @param string $id
     * @return integer numberofRows deleted
     */
    public function deleteById($id)
    {
        $query = 'DELETE FROM products WHERE id = :id ;';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * @return integer $numberOfRow
     */
    public function count()
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM products');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    /**
     * @param associative $product associatve array new product data
     * @return bool true if success, else false
     */
    public function update($product)
    {
        try {
            $query = 'UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, image = :image WHERE id = :id';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id'   => $product['id'],
                ':name' => $product['name'],
                ':description' => $product['description'],
                ':price' => $product['price'],
                ':stock'  => $product['stock'],
                ':image'  => $product['image'],
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * @param associative $id associative product id
     * @param associative $stock associative product stock
     * @param associative $sold associative product sold amount
     * @return bool true if success, else false
     */
    public function buyProduct($id, $stock, $sold)
    {
        try {
            $query = 'UPDATE products SET stock = :stock, sold = sold + :sold WHERE id = :id';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':stock'  => $stock,
                ':id' => $id,
                ':sold' => $sold
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * @param associative $id associative product id
     * @param associative $stock associative product stock
     * @return bool true if success, else false
     */
    public function changeStock($id, $stock)
    {
        try {
            $query = 'UPDATE products SET stock = :stock WHERE id = :id';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':stock'  => $stock,
                ':id' => $id
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }

    /**
     * Adding new stock by name
     * @param associative $name associative product name
     * @param associative $stock associative product stock
     * @return bool true if success, else false
     */
    public function changeStockByName($name, $addedstock)
    {
        echo "NAME " . $name;
        try {
            $query = 'UPDATE products SET stock = stock + :stock WHERE name = :name';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':stock'  => $addedstock,
                ':name' => $name
            ]);
            return true;
        } catch (\PDOException $Exception) {
            return false;
        }
    }
}
