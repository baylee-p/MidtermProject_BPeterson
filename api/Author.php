<?php
class Author {
    private $conn;
    private $table = "authors";

    public $id;
    public $author;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($id = null) {
        $query = "SELECT id, author FROM " . $this->table;

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
        if (empty($this->author)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        $query = "INSERT INTO " . $this->table . " (author) VALUES (:author)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":author", $this->author);
    
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            echo json_encode([
                "id" => $this->id,
                "author" => $this->author
            ]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to create author"]);
        return false;
    }

    // Update Method
    public function update() {
        if (empty($this->id) || empty($this->author)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        $check = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE id = :id");
        $check->bindParam(":id", $this->id);
        $check->execute();
        if ($check->rowCount() === 0) {
            echo json_encode(["message" => "No Authors Found"]);
            return false;
        }
    
        $query = "UPDATE " . $this->table . " SET author = :author WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode([
                "id" => $this->id,
                "author" => $this->author
            ]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to update author"]);
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
            echo json_encode(["message" => "No Authors Found"]);
            return false;
        }
    
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode(["id" => $this->id]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to delete author"]);
        return false;
    }    
}
?>