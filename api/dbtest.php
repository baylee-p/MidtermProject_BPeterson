<?php
include_once "Database.php";

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo json_encode(["status" => "✅ Connected to database"]);
} else {
    echo json_encode(["status" => "❌ Failed to connect"]);
}
?>
