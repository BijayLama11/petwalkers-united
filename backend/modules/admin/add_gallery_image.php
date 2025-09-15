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
    $imageUrl = $conn->real_escape_string($_POST['image_url']);
    $caption = $conn->real_escape_string($_POST['caption']);

    if (empty($imageUrl) || empty($caption)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate URL format
    if (!filter_var($imageUrl, FILTER_VALIDATE_URL) && !preg_match('/^img\//', $imageUrl)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Please provide a valid URL or local image path (starting with img/).']);
        exit;
    }

    $sql = "INSERT INTO gallery_images (image_url, caption) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $imageUrl, $caption);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Image added successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
$conn->close();
?>