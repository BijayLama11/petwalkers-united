<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db_config.php';

// Restrict access: only logged-in admins can use this script
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

// Handle POST request to delete an image
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);

    // Validate image ID
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid image ID.']);
        exit;
    }

    // Delete image by ID
    $sql = "DELETE FROM gallery_images WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Image deleted successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
    }

    $stmt->close();
} else {
    // Reject non-POST requests
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}

$conn->close();
?>