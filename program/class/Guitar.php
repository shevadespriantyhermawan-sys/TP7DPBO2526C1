<?php
class Guitar {
    private $conn;
    private $table = "guitars";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllWithCategory() {
        $sql = "SELECT guitars.*, categories.name AS category_name 
                FROM {$this->table} 
                JOIN categories ON guitars.category_id = categories.id 
                ORDER BY guitars.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($category_id, $brand, $model, $serial_number, $status, $photo) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (category_id, brand, model, serial_number, status, photo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$category_id, $brand, $model, $serial_number, $status, $photo]);
    }

    public function update($id, $category_id, $brand, $model, $serial_number, $status, $photo = null) {
        if ($photo) {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET category_id = ?, brand = ?, model = ?, serial_number = ?, status = ?, photo = ? WHERE id = ?");
            return $stmt->execute([$category_id, $brand, $model, $serial_number, $status, $photo, $id]);
        }
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET category_id = ?, brand = ?, model = ?, serial_number = ?, status = ? WHERE id = ?");
        return $stmt->execute([$category_id, $brand, $model, $serial_number, $status, $id]);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>