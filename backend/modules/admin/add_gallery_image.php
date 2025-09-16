<?php
// Secure session and admin check
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Ensure this path is correct for your setup
require_once '../../config/db_config.php';

// Define the directory where images will be stored
$target_dir = "../../../img/gallery/"; // Path relative to this script

// Set up the response array
$response = ['success' => false, 'message' => ''];

// Check if the directory exists, if not, create it
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

// Check if a file and caption were submitted
if (isset($_FILES["image_file"]) && isset($_POST["caption"])) {

    // Get file details
    $file_tmp_name = $_FILES["image_file"]["tmp_name"];
    $original_filename = basename($_FILES["image_file"]["name"]);

    // Validate file type (optional but recommended)
    $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "png", "jpeg", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        echo json_encode($response);
        exit;
    }

    // Create a unique filename
    $unique_filename = uniqid() . '-' . $original_filename;
    $target_file = $target_dir . $unique_filename;

    // Move the uploaded file
    if (move_uploaded_file($file_tmp_name, $target_file)) {
        // Correct public URL for the image
        $image_url = 'img/gallery/' . $unique_filename;
        $caption = trim($_POST["caption"]);

        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO gallery_images (image_url, caption) VALUES (?, ?)");
        $stmt->bind_param("ss", $image_url, $caption);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Image added successfully!";
        } else {
            $response['message'] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "File upload failed. Please try again.";
    }
} else {
    $response['message'] = "No file or caption submitted.";
}

$conn->close();
echo json_encode($response);
?>