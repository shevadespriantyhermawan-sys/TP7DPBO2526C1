<?php
class Loan {
    private $conn;
    private $table = "loans";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllDetailed() {
        $sql = "SELECT loans.*, members.stage_name AS member_name, 
                       guitars.brand AS guitar_brand, guitars.model AS guitar_model, guitars.serial_number 
                FROM {$this->table}
                JOIN members ON loans.member_id = members.id
                JOIN guitars ON loans.guitar_id = guitars.id
                ORDER BY loans.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByIdDetailed($id) {
        $sql = "SELECT loans.*, members.name AS member_fullname, members.stage_name AS member_name, members.position,
                       guitars.brand AS guitar_brand, guitars.model AS guitar_model, guitars.serial_number, guitars.photo AS guitar_photo
                FROM {$this->table}
                JOIN members ON loans.member_id = members.id
                JOIN guitars ON loans.guitar_id = guitars.id
                WHERE loans.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($member_id, $guitar_id, $loan_date, $notes) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (member_id, guitar_id, loan_date, status, notes) VALUES (?, ?, ?, 'Borrowed', ?)");
        return $stmt->execute([$member_id, $guitar_id, $loan_date, $notes]);
    }

    public function returnGuitar($id, $return_date) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET return_date = ?, status = 'Returned' WHERE id = ?");
        return $stmt->execute([$return_date, $id]);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>