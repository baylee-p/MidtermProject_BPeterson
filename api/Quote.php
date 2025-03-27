<?php
class Quote {
    private $conn;
    private $table = "quotes";

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct ($db) {
        $this->conn = $db;
    }

    // Read all quotes
    public function read() {
        $query = "SELECT
                    q.id, q.quote,
                    a.author,
                    c.category
                FROM " . $this->table . " q
                JOIN authors a ON q.author_id = a.id
                JOIN categories c ON q.category_id = c.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
    }

    // Check author_id exists
    $checkAuthor = $this->conn->prepare("SELECT id FROM authors WHERE id = :id");
    $checkAuthor->bindParam(":id", $this->author_id);
    $checkAuthor->execute();
    if ($checkAuthor->rowCount() === 0) {
        echo json_encode(["message" => "author_id Not Found"]);
        return false;
    }

    // Check category_id exists
    $checkCategory = $this->conn->prepare("SELECT id FROM categories WHERE id = :id");
    $checkCategory->bindParam(":id", $this->category_id);
    $checkCategory->execute();
    if ($checkCategory->rowCount() === 0) {
        echo json_encode(["message" => "category_id Not Found"]);
        return false;
    }

    // Insert quotes
    $query = "INSERT INTO " . $this->table . " (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":quote", $this->quote);
    $stmt->bindParam(":author_id", $this->author_id);
    $stmt->bindParam(":category_id", $this->category_id);

    if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        echo json_encode([
            "id" => $this->id,
            "quote" => $this->quote,
            "author_id" => $this->author_id,
            "category_id" => $this->category_id
        ]);
        return true;
    }

    echo json_encode(["message" => "Unable to create quote"]);
    return false;
    }

    public function update() {
        if (empty($this->id) || empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        // Check if the quote exists
        $checkQuote = $this->conn->prepare("SELECT id FROM quotes WHERE id = :id");
        $checkQuote->bindParam(":id", $this->id);
        $checkQuote->execute();
        if ($checkQuote->rowCount() === 0) {
            echo json_encode(["message" => "No Quotes Found"]);
            return false;
        }
    
        // Check if author_id exists
        $checkAuthor = $this->conn->prepare("SELECT id FROM authors WHERE id = :id");
        $checkAuthor->bindParam(":id", $this->author_id);
        $checkAuthor->execute();
        if ($checkAuthor->rowCount() === 0) {
            echo json_encode(["message" => "author_id Not Found"]);
            return false;
        }
    
        // Check if category_id exists
        $checkCategory = $this->conn->prepare("SELECT id FROM categories WHERE id = :id");
        $checkCategory->bindParam(":id", $this->category_id);
        $checkCategory->execute();
        if ($checkCategory->rowCount() === 0) {
            echo json_encode(["message" => "category_id Not Found"]);
            return false;
        }
    
        // Update the quote
        $query = "UPDATE quotes SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":quote", $this->quote);
        $stmt->bindParam(":author_id", $this->author_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode([
                "id" => $this->id,
                "quote" => $this->quote,
                "author_id" => $this->author_id,
                "category_id" => $this->category_id
            ]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to update quote"]);
        return false;
    }   
    
    // Delete Method
    public function delete() {
        if (empty($this->id)) {
            echo json_encode(["message" => "Missing Required Parameters"]);
            return false;
        }
    
        // Check if quote exists
        $check = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE id = :id");
        $check->bindParam(":id", $this->id);
        $check->execute();
        if ($check->rowCount() === 0) {
            echo json_encode(["message" => "No Quotes Found"]);
            return false;
        }
    
        // Delete the quote
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            echo json_encode(["id" => $this->id]);
            return true;
        }
    
        echo json_encode(["message" => "Unable to delete quote"]);
        return false;
    }    
}
?>