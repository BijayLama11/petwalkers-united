<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: /petwalkers-united/login.html");
    exit;
}

require_once '../../config/db_config.php';

$sql = "SELECT id, first_name, last_name, email, phone, role FROM users ORDER BY id DESC";
$result = $conn->query($sql);
$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
                    <li><a href="manage_services.php">Manage Services</a></li>
                    <li><a href="manage_users.php" class="active">Manage Users</a></li>
                    <li><a href="../../logout.php" class="logout-link">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <section class="content">
            <h2>Manage Users</h2>
            <div id="status-message" class="status"></div>

            <form id="add-user-form" class="admin-form">
                <h3>Add New User</h3>
                <div class="field">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="firstName" required>
                </div>
                <div class="field">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="lastName" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="field">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>

            <h3>Registered Users</h3>
            <?php if (count($users) > 0): ?>
                <table id="users-table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <button class="btn btn-danger delete-user-btn"
                                        data-id="<?php echo $user['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </section>
    </main>
    <script src="/js/main.js"></script>
    <script src="/js/admin.js"></script>
</body>

</html>