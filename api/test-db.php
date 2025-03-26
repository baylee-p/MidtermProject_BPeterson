<?php
include_once "api/Database.php";

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "✅ Connected to the database!";
} else {
    echo "❌ Connection failed.";
}
?>
