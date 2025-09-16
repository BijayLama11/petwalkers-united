<?php
// backend/dashboard.php
session_start();

// Check if the user is logged in AND has the 'admin' role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: /petwalkers-united/login.html");
    exit;
}

require_once '../../config/db_config.php';

// Fetch all contact submissions
$sql = "SELECT id, name, email, phone, subject, message, submission_date FROM contact_submissions ORDER BY submission_date DESC";
$result = $conn->query($sql);
$contacts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../../css/main.css">
    <link rel="stylesheet" href="../../../css/admin.css">
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
                    <li><a href="dashboard.php" class="active">Contact Submissions</a></li>
                    <li><a href="manage_services.php">Manage Services</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="manage_gallery.php">Manage Gallery</a></li>
                    <li><a href="../../logout.php" class="logout-link">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <section class="content">
            <h2>Contact Submissions</h2>
            <?php if (count($contacts) > 0): ?>
                <table class="dashborad-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                                <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                <td><?php echo htmlspecialchars($contact['submission_date']); ?></td>

                                <td>
                                    <?php
                                    $msg = $contact['message'];
                                    $msg = str_replace(['\\r\\n', '\\n', '\\r'], "\n", $msg);
                                    echo nl2br(htmlspecialchars($msg));
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-danger delete-contact-btn"
                                        data-id="<?php echo $contact['id']; ?>">Delete</button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No contact submissions found.</p>
            <?php endif; ?>
        </section>
    </main>

    <script src="../../../js/main.js"></script>
</body>

</html>