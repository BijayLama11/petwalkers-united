<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db_config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceName = $conn->real_escape_string($_POST['service_name']);
    $subtitle = $conn->real_escape_string($_POST['subtitle']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];

    if (empty($serviceName) || empty($description) || empty($price)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $sql = "INSERT INTO services (service_name, subtitle, description, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $serviceName, $subtitle, $description, $price);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Service added successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' =>  'An internal server error occurred.']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
$conn->close();
?>