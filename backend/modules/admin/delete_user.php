<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db_config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);

    // Validate the ID
    if (empty($id) || $id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
        exit;
    }

    // Prevent admin from deleting their own account
    if ($id == $_SESSION['id']) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'You cannot delete your own account.']);
        exit;
    }

    // Check if user exists before attempting to delete
    $checkSql = "SELECT id, first_name, last_name FROM users WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        $checkStmt->close();
        exit;
    }

    $user = $result->fetch_assoc();
    $checkStmt->close();

    // Delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => "User {$user['first_name']} {$user['last_name']} deleted successfully."
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete user from database.']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}

$conn->close();
?>