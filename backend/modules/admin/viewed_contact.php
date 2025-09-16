<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db_config.php';

// Restrict access: only logged-in admins can perform this action
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

// Handle POST request to mark a contact submission as viewed
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);

    // Validate contact ID
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid contact ID.']);
        exit;
    }

    // Update contact record to set "viewed" status
    $sql = "UPDATE contact_submissions SET viewed = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Contact marked as viewed.']);
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
