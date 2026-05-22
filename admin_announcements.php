<?php
require_once 'admin_navbar.php';

require 'db_connect.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_url = $_POST['image_url'];
    $id = $_POST['id'] ?? null;

    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($id) {
        $stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$title, $content, $image_url, $id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, image_url) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $image_url]);
    }

    header("Location: admin_announcements.php");
    exit;
}

// Get all announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <title>Admin Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }
        .filter-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .announcement-image-preview {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
        }
        .table-responsive {
            min-height: 400px;
        }
        .modal-content img {
            max-width: 100%;
            max-height: 300px;
            display: block;
            margin: 10px auto;
        }
        .content-preview {
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4"><i class="fas fa-bullhorn me-2"></i>Manage Announcements</h2>
        
        <!-- Create New Announcement Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="fas fa-plus-circle me-1"></i> Create Announcement
            </button>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="filter-container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search announcements...">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <select id="dateFilter" class="form-select">
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Announcements Table -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><i class="fas fa-list me-2"></i>Existing Announcements</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content Preview</th>
                                <th>Date Posted</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="announcementTableBody">
                            <?php foreach ($announcements as $a): ?>
                                <tr>
                                    <td><?= htmlspecialchars($a['id']) ?></td>
                                    <td><?= htmlspecialchars($a['title']) ?></td>
                                    <td class="content-preview"><?= htmlspecialchars($a['content']) ?></td>
                                    <td><?= date('M j, Y', strtotime($a['date_posted'])) ?></td>
                                    <td>
                                        <?php if ($a['image_url']): ?>
                                            <img src="<?= htmlspecialchars($a['image_url']) ?>" alt="Announcement image" class="announcement-image-preview" onerror="this.style.display='none'">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-btn" data-id="<?= $a['id'] ?>">
                                            <i class="fas fa-eye"></i> View/Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $a['id'] ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Announcement Modal -->
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="newTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="newTitle" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newContent" class="form-label">Content</label>
                            <textarea class="form-control" id="newContent" name="content" rows="8" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newImageUrl" class="form-label">Image URL (optional)</label>
                            <input type="text" class="form-control" id="newImageUrl" name="image_url" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Post Announcement
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View/Edit Announcement Modal -->
    <div class="modal fade" id="viewAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Announcement Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editAnnouncementForm" method="POST">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editContent" class="form-label">Content</label>
                            <textarea class="form-control" id="editContent" name="content" rows="8" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editImageUrl" class="form-label">Image URL</label>
                            <input type="text" class="form-control" id="editImageUrl" name="image_url">
                        </div>
                        
                        <div id="imagePreviewContainer" class="text-center mb-3"></div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Announcement
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewModal = new bootstrap.Modal(document.getElementById('viewAnnouncementModal'));
            const createModal = new bootstrap.Modal(document.getElementById('createAnnouncementModal'));
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('dateFilter');
            const announcementTableBody = document.getElementById('announcementTableBody');
            const rows = announcementTableBody.getElementsByTagName('tr');
            
            // Filter announcements based on search and date selection
            function filterAnnouncements() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedDate = dateFilter.value;
                const today = new Date();
                
                for (let row of rows) {
                    const title = row.cells[1].textContent.toLowerCase();
                    const content = row.cells[2].textContent.toLowerCase();
                    const dateText = row.cells[3].textContent;
                    const rowDate = new Date(dateText);
                    
                    const matchesSearch = title.includes(searchTerm) || content.includes(searchTerm) || searchTerm === '';
                    
                    let matchesDate = true;
                    if (selectedDate === 'today') {
                        matchesDate = rowDate.toDateString() === today.toDateString();
                    } else if (selectedDate === 'week') {
                        const oneWeekAgo = new Date();
                        oneWeekAgo.setDate(today.getDate() - 7);
                        matchesDate = rowDate >= oneWeekAgo;
                    } else if (selectedDate === 'month') {
                        matchesDate = rowDate.getMonth() === today.getMonth() && 
                                      rowDate.getFullYear() === today.getFullYear();
                    }
                    
                    row.style.display = (matchesSearch && matchesDate) ? '' : 'none';
                }
            }
            
            // Event listeners for search and filter
            searchInput.addEventListener('input', filterAnnouncements);
            dateFilter.addEventListener('change', filterAnnouncements);
            
            // View announcement details
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const announcementId = btn.dataset.id;
                    const row = btn.closest('tr');
                    
                    // Get data from the table row
                    const title = row.cells[1].textContent;
                    const content = row.cells[2].textContent;
                    const imageUrl = row.cells[4].querySelector('img')?.src || '';
                    
                    // Populate the modal form
                    document.getElementById('editId').value = announcementId;
                    document.getElementById('editTitle').value = title;
                    document.getElementById('editContent').value = content;
                    document.getElementById('editImageUrl').value = imageUrl;
                    
                    // Update image preview
                    const imageContainer = document.getElementById('imagePreviewContainer');
                    imageContainer.innerHTML = '';
                    
                    if (imageUrl) {
                        const img = document.createElement('img');
                        img.src = imageUrl;
                        img.alt = 'Announcement image';
                        img.onerror = function() {
                            this.style.display = 'none';
                        };
                        imageContainer.appendChild(img);
                    }
                    
                    viewModal.show();
                });
            });
            
            // Delete announcement
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (confirm("Are you sure you want to delete this announcement?")) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';
                        
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = 'id';
                        idInput.value = btn.dataset.id;
                        
                        const deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = 'delete';
                        deleteInput.value = '1';
                        
                        form.appendChild(idInput);
                        form.appendChild(deleteInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>