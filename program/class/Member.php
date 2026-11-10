<?php
class Member {
    private $conn;
    private $table_name = "members";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $stage_name, $position, $photo) {
        $query = "INSERT INTO " . $this->table_name . " (name, stage_name, position, photo) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $stage_name, $position, $photo]);
    }

    public function update($id, $name, $stage_name, $position, $photo = null) {
        if ($photo) {
            $query = "UPDATE " . $this->table_name . " SET name = ?, stage_name = ?, position = ?, photo = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $stage_name, $position, $photo, $id]);
        } else {
            $query = "UPDATE " . $this->table_name . " SET name = ?, stage_name = ?, position = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $stage_name, $position, $id]);
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>