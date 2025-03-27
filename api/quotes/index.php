<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Headers
header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    }
// Include Files
include_once "../Database.php";
include_once "../Quote.php";

// Instantiate DB and connect
$database = new Database();
$db = $database->getConnection();

// Instantiate Quote
$quote = new Quote($db);

// Get Quotes
$result = $quote->read();
$num = $result->rowCount();

// Get filter parameters
$id = isset($_GET['id']) ? $_GET['id'] : null;
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Build dynamic query
$baseQuery = "SELECT q.id, q.quote, a.author, c.category
              FROM quotes q
              JOIN authors a ON q.author_id = a.id
              JOIN categories c ON q.category_id = c.id";

$where = [];
$params = [];

if ($id) {
    $where[] = "q.id = :id";
    $params[':id'] = $id;
}

if ($author_id) {
    $where[] = "q.author_id = :author_id";
    $params[':author_id'] = $author_id;
}

if ($category_id) {
    $where[] = "q.category_id = :category_id";
    $params[':category_id'] = $category_id;
}

if (!empty($where)) {
    $baseQuery .= " WHERE " . implode(" AND ", $where);
}

$stmt = $db->prepare($baseQuery);
$stmt->execute($params);

// Check if any quotes exist
if ($stmt->rowCount() > 0) {
    if ($id) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            "id" => $row['id'],
            "quote" => $row['quote'],
            "author" => $row['author'],
            "category" => $row['category']
        ]);
    } else {
    
        $quotes_arr = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $quotes_arr[] = [
                "id" => $row['id'],
                "quote" => $row['quote'],
                "author" => $row['author'],
                "category" => $row['category']
            ];
        }

        echo json_encode($quotes_arr);
    }
} else {
    echo json_encode (["message" => "No Quotes Found"]);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $quote->quote = $data->quote ?? '';
    $quote->author_id = $data->author_id ?? '';
    $quote->category_id = $data->category_id ?? '';

    $quote->create();
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    $quote->id = $data->id ?? '';
    $quote->quote = $data->quote ?? '';
    $quote->author_id = $data->author_id ?? '';
    $quote->category_id = $data->category_id ?? '';

    $quote->update();
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $quote->id = $data->id ?? '';
    $quote->delete();
}
?>