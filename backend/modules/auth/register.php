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
        echo json_encode(['message' => 'All fields are required.']);
        exit;
    }

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['message' => 'Email already in use.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Registration successful! You can now log in.']);
    } else {
        echo json_encode(['message' => 'An internal server error occurred.']);
    }

    $stmt->close();
}

$conn->close();
?>