<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: /petwalkers-united/login.html");
    exit;
}

require_once '../../config/db_config.php';

$sql = "SELECT id, image_url, caption, upload_date FROM gallery_images ORDER BY upload_date DESC";
$result = $conn->query($sql);
$images = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery</title>
    <link rel="stylesheet" href="../../../css/main.css">
    <link rel="stylesheet" href="../../../css/admin.css">
    <link rel="stylesheet" href="../../../css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .gallery-item {
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }
        
        .gallery-item-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .gallery-item-caption {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .gallery-item-date {
            font-size: 0.8rem;
            color: #999;
        }
    </style>
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
                    <li><a href="dashboard.php">Contact Submissions</a></li>
                    <li><a href="manage_services.php">Manage Services</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="manage_gallery.php" class="active">Manage Gallery</a></li>
                    <li><a href="../../logout.php" class="logout-link">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <section class="content">
            <h2>Manage Gallery</h2>
            <div id="status-message" class="status"></div>

            <form id="add-image-form" class="admin-form">
                <h3>Add New Image</h3>
                <div class="field">
                    <label for="image-url">Image Path/URL</label>
                    <input type="text" id="image-url" name="image_url" required placeholder="e.g., img/gallery/dog-happy.jpg or https://example.com/image.jpg">
                    <small style="color: #666; font-size: 0.85rem;">Enter a local path starting with 'img/' or a full URL</small>
                </div>
                <div class="field">
                    <label for="caption">Caption</label>
                    <input type="text" id="caption" name="caption" required placeholder="e.g., Happy dog after morning walk">
                </div>
                <button type="submit" class="btn btn-primary">Add Image</button>
            </form>

            <h3>Gallery Images (<?php echo count($images); ?> total)</h3>
            <?php if (count($images) > 0): ?>
                <div class="gallery-grid">
                    <?php foreach ($images as $image): ?>
                        <div class="gallery-item">
                            <img src="../../../<?php echo htmlspecialchars($image['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['caption']); ?>"
                                 onerror="this.src='../../../img/placeholder.jpg';">
                            <div class="gallery-item-info">
                                <div class="gallery-item-caption">
                                    <?php echo htmlspecialchars($image['caption']); ?>
                                </div>
                                <div class="gallery-item-date">
                                    Added: <?php echo date('M j, Y', strtotime($image['upload_date'])); ?>
                                </div>
                            </div>
                            <button class="btn btn-danger delete-image-btn" data-id="<?php echo $image['id']; ?>">
                                Delete Image
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem; background: #f9f9f9; border-radius: 8px; margin-top: 2rem;">
                    <p style="color: #666; font-size: 1.1rem; margin: 0;">
                        No images in gallery yet. Add some images using the form above!
                    </p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script src="../../../js/main.js"></script>
    <script>
        function showStatus(type, message) {
            const statusDiv = document.getElementById('status-message');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
            setTimeout(() => {
                statusDiv.className = 'status';
            }, 5000);
        }

        // Add image functionality
        document.getElementById('add-image-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('add_gallery_image.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    showStatus('success', result.message);
                    this.reset();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showStatus('error', result.message);
                }
            } catch (error) {
                showStatus('error', 'An unexpected error occurred.');
            }
        });

        // Delete image functionality
        document.addEventListener('click', async function(e) {
            if (e.target.classList.contains('delete-image-btn')) {
                if (confirm('Are you sure you want to delete this image? This action cannot be undone.')) {
                    const imageId = e.target.dataset.id;
                    const formData = new FormData();
                    formData.append('id', imageId);
                    
                    try {
                        const response = await fetch('delete_gallery_image.php', {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();
                        
                        if (result.success) {
                            showStatus('success', result.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showStatus('error', result.message);
                        }
                    } catch (error) {
                        showStatus('error', 'An unexpected error occurred.');
                    }
                }
            }
        });
    </script>
</body>
</html>