<?php

// Database configuration
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "petwalkers_db";
$db_port   = 3306;

// Connect to MySQL server
$conn = new mysqli($db_server, $db_user, $db_pass, "", $db_port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// 1. Create the main database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database '$db_name' created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($db_name);

// 2. SQL to create the `Users` table
$sql_create_users_table = "
CREATE TABLE IF NOT EXISTS Users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') DEFAULT 'user',
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";

// 3. SQL to create the `contact_submissions` table
$sql_create_contact_table = "
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// 4. SQL to create the `gallery_images` table
$sql_create_gallery_table = "
CREATE TABLE IF NOT EXISTS gallery_images (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  image_url VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NOT NULL,
  upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

// 5. SQL to create the `services` table
$sql_create_services_table = "
CREATE TABLE IF NOT EXISTS Services (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(100) NOT NULL,
  subtitle TEXT NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";

// Execute all table creation queries
if ($conn->query($sql_create_users_table) === TRUE) {
    echo "Table 'users' created successfully or already exists.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

if ($conn->query($sql_create_contact_table) === TRUE) {
    echo "Table 'contact_submissions' created successfully or already exists.<br>";
} else {
    echo "Error creating table 'contact_submissions': " . $conn->error . "<br>";
}

if ($conn->query($sql_create_gallery_table) === TRUE) {
    echo "Table 'gallery_images' created successfully or already exists.<br>";
} else {
    echo "Error creating table 'gallery_images': " . $conn->error . "<br>";
}

if ($conn->query($sql_create_services_table) === TRUE) {
    echo "Table 'services' created successfully or already exists.<br>";
} else {
    echo "Error creating table 'services': " . $conn->error . "<br>";
}

$adminEmail = '20033379@students.koi.edu.au';

$checkAdminSql = "SELECT id FROM users WHERE email = '$adminEmail'";
$adminResult = $conn->query($checkAdminSql);

if ($adminResult->num_rows == 0) {
    $hashedPassword = password_hash("admin123", PASSWORD_DEFAULT);
    $insertAdminSql = "INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES ('Bijaya', 'Lama', '$adminEmail', '0123456789', '$hashedPassword', 'admin')";
    if ($conn->query($insertAdminSql) === TRUE) {
        echo "Default admin user inserted successfully.<br>";
    } else {
        echo "Error inserting admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Admin user already exists.<br>";
}

$conn->close();

?>