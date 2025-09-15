<?php
// Script to add subtitle column to services table if it doesn't exist
require_once 'backend/config/db_config.php';

echo "<h2>Services Table Update</h2>";

// Check if subtitle column exists
$sql = "SHOW COLUMNS FROM services LIKE 'subtitle'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Subtitle column doesn't exist, add it
    $alterSql = "ALTER TABLE services ADD COLUMN subtitle VARCHAR(255) NOT NULL DEFAULT '' AFTER service_name";
    
    if ($conn->query($alterSql) === TRUE) {
        echo "<p style='color: green;'>✓ Subtitle column added successfully!</p>";
        
        // Update existing services with default subtitle
        $updateSql = "UPDATE services SET subtitle = CONCAT('Professional ', LOWER(service_name), ' service') WHERE subtitle = ''";
        if ($conn->query($updateSql) === TRUE) {
            echo "<p style='color: green;'>✓ Existing services updated with default subtitles!</p>";
        } else {
            echo "<p style='color: orange;'>Warning: Could not update existing services: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Error adding subtitle column: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>ℹ Subtitle column already exists!</p>";
}

// Show current table structure
echo "<h3>Current Services Table Structure:</h3>";
$result = $conn->query("DESCRIBE services");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Show current services
echo "<h3>Current Services:</h3>";
$result = $conn->query("SELECT id, service_name, subtitle, price FROM services");
if ($result && $result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Service Name</th><th>Subtitle</th><th>Price</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['subtitle'] ?? '') . "</td>";
        echo "<td>$" . number_format($row['price'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No services found.</p>";
}

$conn->close();

echo "<br><p><em>Update completed. You can now safely delete this file.</em></p>";
echo "<p><a href='backend/modules/admin/dashboard.php'>← Go to Admin Dashboard</a></p>";
?>