<?php
header('Content-Type: application/json');

require_once '../../config/db_config.php';

$services = [];
$sql = "SELECT service_name, description, price FROM services ORDER BY price";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

echo json_encode($services);

$conn->close();
?>