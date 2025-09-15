<?php
header('Content-Type: application/json');

require_once '../../config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required.']);
        exit;
    }

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['message' => 'Email already in use.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user with 'user' role (not admin)
    $role = 'user'; // Force all new registrations to be 'user' role
       $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $phone, $hashed_password, $role);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Registration successful! You can now log in.']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'An internal server error occurred.']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}

$conn->close();
?>