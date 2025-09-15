<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: /petwalkers-united/login.html");
    exit;
}

require_once '../../config/db_config.php';

$sql = "SELECT id, service_name, description, price FROM services ORDER BY id ASC";
$result = $conn->query($sql);
$services = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="../../../css/main.css">
    <link rel="stylesheet" href="../../../css/admin.css">
    <link rel="stylesheet" href="../../../css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <header class="admin-header">
        <div class="container header-inner">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <main class="container admin-main">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="dashboard.php">Contact Submissions</a></li>
                    <li><a href="manage_services.php" class="active">Manage Services</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="../../logout.php" class="logout-link">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <section class="content">
            <h2>Manage Services</h2>
            <div id="status-message" class="status"></div>

            <form id="add-service-form" class="admin-form" method="POST" action="add_service.php">
                <h3>Add New Service</h3>
                <div class="field">
                    <label for="service-name">Service Name</label>
                    <input type="text" id="service-name" name="service_name" required>
                </div>
                <div class="field">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" id="subtitle" name="subtitle" required>
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="field">
                    <label for="price">Price (AUD)</label>
                    <input type="number" step="0.01" id="price" name="price" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Service</button>
            </form>

            <h3>Existing Services</h3>
            <?php if (count($services) > 0): ?>
                <table id="services-table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Subtitle</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr data-id="<?php echo $service['id']; ?>">
                                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($service['subtitle']); ?></td>
                                <td><?php echo htmlspecialchars($service['description']); ?></td>
                                <td>AUD <?php echo htmlspecialchars(number_format($service['price'], 2)); ?></td>
                                <td>
                                    <button class="btn btn-danger delete-btn"
                                        data-id="<?php echo $service['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No services found. Add one using the form above.</p>
            <?php endif; ?>
        </section>
    </main>

    <script src="../../../js/main.js"></script>
    <script src="../../../js/admin.js"></script>
</body>

</html>