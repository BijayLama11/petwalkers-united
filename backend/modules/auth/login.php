<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/db_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Email and password are required.']);
        exit;
    }

    $sql = "SELECT id, first_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $firstName, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['role'] = $role;
            http_response_code(200);
            echo json_encode(['message' => 'Login successful!']);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid email or password.']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid email or password.']);
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}

$conn->close();
?>