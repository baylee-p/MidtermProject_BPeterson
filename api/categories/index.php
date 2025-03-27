<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }
    
parse_str(file_get_contents("php://input"), $_PUT);

include_once "../Database.php";
include_once "../Category.php";

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$id = isset($_GET['id']) ? $_GET['id'] : null;

$stmt = $category->read($id);

if ($stmt->rowCount() > 0) {
    $results = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }

    echo json_encode($id ? $results[0] : $results);
} else {
    echo json_encode(["message" => $id ? "category_id Not Found" : "No Categories Found"]);
}

// POST Method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $category->category = $data->category ?? '';
    $category->create();
}

// PUT Method
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    $category->id = $data->id ?? '';
    $category->category = $data->category ?? '';

    $category->update();
}

// DELETE Method
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $category->id = $data->id ?? '';
    $category->delete();
}
?>