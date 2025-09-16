<?php
header('Content-Type: application/json');

require_once '../../config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    $sql = "INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
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