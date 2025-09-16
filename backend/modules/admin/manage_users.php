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
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</p>
        </div>
    </header>
    <main class="container admin-main">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="/petwalkers-united/index.html">Home</a></li>
                    <li><a href="dashboard.php">Contact Submissions</a></li>
                    <li><a href="manage_services.php">Manage Services</a></li>
                    <li><a href="manage_users.php" class="active">Manage Users</a></li>
                    <li><a href="manage_gallery.php">Manage Gallery</a></li>
                    <li><a href="../../logout.php" class="logout-link">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <section class="content" style="display: flex;">
            <div class="user-container">
            <h2>Manage Users</h2>
            <div id="status-message" class="status"></div>

            <form id="add-user-form" class="admin-form" method="POST" action="#">
                <h3>Add New User</h3>
                <div class="field">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required minlength="2" maxlength="50">
                </div>
                <div class="field">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required minlength="2" maxlength="50">
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required maxlength="100">
                </div>
                <div class="field">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" required maxlength="20" placeholder="e.g., 0123456789">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="6" maxlength="255">
                    <small style="color: #666; font-size: 0.9em;">Minimum 6 characters</small>
                </div>
                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select a role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
            </div>
            <div style="display: block;">
            <h2>Registered Users</h2>
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
                                    <?php if ($user['id'] != $_SESSION['id']): ?>
                                        <button class="btn btn-danger delete-user-btn"
                                            data-id="<?php echo $user['id']; ?>">Delete</button>
                                    <?php else: ?>
                                        <span style="color: #666; font-style: italic;">Current User</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="../../../js/main.js"></script>
    <script src="../../../js/admin.js"></script>
</body>

</html>