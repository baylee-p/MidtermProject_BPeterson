<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once "../Database.php";
include_once "../Author.php";

$database = new Database();
$db = $database->getConnection();

$author = new Author($db);
$id = isset($_GET['id']) ? $_GET['id'] : null;

$stmt = $author->read($id);

if ($stmt->rowCount() > 0) {
    $results = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }

    echo json_encode($id ? $results[0] : $results);
} else {
    echo json_encode(["message" => $id ? "author_id Not Found" : "No Authors Found"]);
}

// POST Method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $author->author = $data->author ?? '';
    $author->create();
}

// PUT Method
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    $author->id = $data->id ?? '';
    $author->author = $data->author ?? '';

    $author->update();
}

// DELETE Method
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $author->id = $data->id ?? '';
    $author->delete();
}
?>