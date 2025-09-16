<?php
session_start(); // Start the session to manage user authentication
header('Content-Type: application/json'); // Set response type to JSON
require_once '../../config/db_config.php'; // Include database configuration

// Check if user is logged in and has admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and escape input values
    $serviceName = $conn->real_escape_string($_POST['service_name']);
    $subtitle = $conn->real_escape_string($_POST['subtitle']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];

    // Validate required fields
    if (empty($serviceName) || empty($description) || empty($price)) {
        http_response_code(400); // Bad request
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Prepare SQL statement to insert new service
    $sql = "INSERT INTO services (service_name, subtitle, description, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $serviceName, $subtitle, $description, $price);

    // Execute query and send response
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Service added successfully.']);
    } else {
        http_response_code(500); // Internal server error
        echo json_encode(['success' => false, 'message' =>  'An internal server error occurred.']);
    }

    $stmt->close(); // Close statement
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}

$conn->close(); // Close database connection
?>
