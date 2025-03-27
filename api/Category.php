<?php
class Category {
    private $conn;
    private $table = "categories";

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($id = null) {
        $query = "SELECT id, category FROM " . $this->table;

        if ($id) {
            $query .= " WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        if ($id) {
            $stmt->bindParam(":id", $id);
        }

        $stmt->execute();
        return $stmt;
    }

    // Create Method
    public function create() {
        if (empty($this->category)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        $query = "INSERT INTO " . $this->table . " (category) VALUES (:category)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $this->category);
    
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            echo json_encode([
                "id" => $this->id,
                "category" => $this->category
            ]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to create category"]);
        return false;
    }

    // Update Method
    public function update() {
        if (empty($this->id) || empty($this->category)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        $check = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE id = :id");
        $check->bindParam(":id", $this->id);
        $check->execute();
        if ($check->rowCount() === 0) {
            echo json_encode(["message" => "No Categories Found"]);
            return false;
        }
    
        $query = "UPDATE " . $this->table . " SET category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode([
                "id" => $this->id,
                "category" => $this->category
            ]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to update category"]);
        return false;
    }

    // Delete Method
    public function delete() {
        if (empty($this->id)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        $check = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE id = :id");
        $check->bindParam(":id", $this->id);
        $check->execute();
        if ($check->rowCount() === 0) {
            echo json_encode(["message" => "No Categories Found"]);
            return false;
        }
    
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode(["id" => $this->id]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to delete category"]);
        return false;
    }    
}
?>
