<?php
header("Content-Type: application/json");

include 'config.php';
// Handle API requests
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($method) {
    case 'GET':
        if ($endpoint === 'products') {
            // Retrieve list of items
            retrieveProducts($conn);
        } elseif (preg_match('/^products\/(\d+)$/', $endpoint, $matches)) {
            // Retrieve details of a specific item
            retrieveProduct($conn, $matches[1]);
        }
        break;
    case 'POST':
        if ($endpoint === 'products') {
            // Add a new item
            addProduct($conn);
        }
        break;
    case 'PUT':
        if (preg_match('/^products\/(\d+)$/', $endpoint, $matches)) {
            // Update an existing item
            updateProduct($conn, $matches[1]);
        }
        break;
    case 'DELETE':
        if (preg_match('/^products\/(\d+)$/', $endpoint, $matches)) {
            // Delete an item
            deleteProduct($conn, $matches[1]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

$conn->close();

// Functions
function retrieveProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    
    echo json_encode($products);
}

function retrieveProduct($conn, $id) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    echo json_encode($product);
}

function addProduct($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];

    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $name, $description, $price);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Product added"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to add product"]);
    }
}

function updateProduct($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $name, $description, $price, $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to update product"]);
    }
}

function deleteProduct($conn, $id) {
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product deleted"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to delete product"]);
    }
}
