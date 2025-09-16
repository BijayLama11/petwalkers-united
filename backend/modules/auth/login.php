<?php
session_start();
header('Content-Type: application/json');
require_once '../../config/db_config.php';

// Handle login via POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Validate required fields
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Email and password are required.']);
        exit;
    }

    // Check if user exists with given email
    $sql = "SELECT id, first_name, last_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If user found, verify password
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $firstName, $lastName, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Set session variables on successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            http_response_code(200);
            echo json_encode([
                'message' => 'Login successful!',
                'role' => $role,
                'firstName' => $firstName
            ]);
        } else {
            // Wrong password
            http_response_code(401);
            echo json_encode(['message' => 'Invalid email or password.']);
        }
    } else {
        // No user found with given email
        http_response_code(401);
        echo json_encode(['message' => 'Invalid email or password.']);
    }

    $stmt->close();
} else {
    // Reject non-POST requests
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}

$conn->close();
?>