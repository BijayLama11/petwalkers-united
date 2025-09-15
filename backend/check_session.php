<?php
session_start();
header('Content-Type: application/json');

$response = [
    'logged_in' => false,
    'role' => null,
    'firstName' => null
];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['logged_in'] = true;
    $response['role'] = $_SESSION['role'];
    $response['firstName'] = $_SESSION['firstName'];
}

echo json_encode($response);
?>