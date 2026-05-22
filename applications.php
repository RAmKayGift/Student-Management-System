<?php
require 'db_connect.php';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_POST['id_number']; // Changed from student_id to id_number
    $action = $_POST['action'];
    
    try {
        if ($action === 'accept') {
            // First get the actual student_id (integer) using the id_number
            $stmt = $conn->prepare("SELECT student_id FROM students WHERE id_number = ?");
            $stmt->execute([$id_number]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$student) {
                throw new Exception("Student not found");
            }
            
            $student_id = $student['student_id'];
            
            // Update application status using id_number
            $stmt = $conn->prepare("UPDATE students SET application_status = 'Accepted' WHERE id_number = ?");
            $stmt->execute([$id_number]);
            
            // Add to enrolled_students using the correct student_id (integer)
            $stmt = $conn->prepare("INSERT INTO enrolled_students (student_id, enrollment_date) VALUES (?, NOW())");
            $stmt->execute([$student_id]);
            
            $message = "Application accepted and student enrolled successfully!";
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE students SET application_status = 'Rejected' WHERE id_number = ?");
            $stmt->execute([$id_number]);
            
            $message = "Application rejected successfully!";
        }
        
        // Refresh to show updated list
        header("Location: applications.php?message=" . urlencode($message));
        exit;
        
    } catch (Exception $e) {
        $error = "Error processing application: " . $e->getMessage();
        header("Location: applications.php?error=" . urlencode($error));
        exit;
    }
}

// Check if we're viewing a student's full details
$viewingDetails = isset($_GET['view']) && $_GET['view'] === 'details';
$currentStudent = null;

if ($viewingDetails && isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id_number = ?");
    $stmt->execute([$_GET['id']]);
    $currentStudent = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $viewingDetails ? 'Student Details' : 'Pending Applications'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #1a73e8;
      --primary-blue-dark: #0d5bbc;
      --primary-blue-light: #e8f0fe;
    }
    
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .application-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      margin-bottom: 1rem;
      border-left: 4px solid var(--primary-blue);
      transition: all 0.3s;
    }
    
    .application-card:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    
    .application-header {
      background-color: var(--primary-blue-light);
      padding: 1rem;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .application-body {
      padding: 1.5rem;
    }
    
    .detail-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    .detail-item strong {
      color: #555;
      display: block;
      margin-bottom: 0.25rem;
      font-size: 0.9rem;
    }
    
    .btn-group-actions {
      display: flex;
      gap: 0.5rem;
      justify-content: flex-end;
    }
    
    .status-badge {
      padding: 0.35rem 0.65rem;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .status-accepted {
      background-color: #d4edda;
      color: #155724;
    }
    
    .status-rejected {
      background-color: #f8d7da;
      color: #721c24;
    }
    
    /* Student Details Modal Styles */
    .student-details-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1050;
      padding: 20px;
    }
    
    .student-details-content {
      background-color: white;
      border-radius: 8px;
      width: 100%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .student-details-header {
      background-color: var(--primary-blue);
      color: white;
      padding: 1rem;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .student-details-body {
      padding: 1.5rem;
    }
    
    .detail-section {
      margin-bottom: 1.5rem;
    }
    
    .detail-section h4 {
      color: var(--primary-blue);
      border-bottom: 1px solid #eee;
      padding-bottom: 0.5rem;
      margin-bottom: 1rem;
    }
    
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    
    @media (max-width: 768px) {
      .detail-row {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .detail-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <?php include 'admin_navbar.php'; ?>
  
  <div class="container py-4">
    <?php if ($viewingDetails && $currentStudent): ?>
      <!-- Student Details View -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-graduate me-2"></i>Student Details</h2>
        <a href="applications.php" class="btn btn-outline-primary">
          <i class="fas fa-arrow-left me-1"></i> Back to Applications
        </a>
      </div>
      
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
          <?php echo htmlspecialchars($_GET['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <div class="application-card">
        <div class="application-header">
          <div>
            <h5 class="mb-0"><?= htmlspecialchars($currentStudent['first_name'] . ' ' . $currentStudent['last_name']) ?></h5>
            <small class="text-muted">ID: <?= htmlspecialchars($currentStudent['id_number']) ?></small>
          </div>
          <?php $status = $currentStudent['application_status'] ?? 'Pending'; ?>
          <span class="status-badge status-<?= strtolower($status) ?>">
            <i class="fas fa-<?= $status === 'Pending' ? 'clock' : ($status === 'Accepted' ? 'check' : 'times') ?> me-1"></i> <?= $status ?>
          </span>
        </div>
        
        <div class="application-body">
          <div class="detail-section">
            <h4><i class="fas fa-info-circle me-2"></i>Personal Information</h4>
            <div class="detail-grid">
              <div class="detail-item">
                <strong>Date of Birth</strong>
                <?= date('M j, Y', strtotime($currentStudent['dob'])) ?>
              </div>
              <div class="detail-item">
                <strong>Gender</strong>
                <?= htmlspecialchars($currentStudent['gender']) ?>
              </div>
              <div class="detail-item">
                <strong>Email</strong>
                <?= htmlspecialchars($currentStudent['email']) ?>
              </div>
              <div class="detail-item">
                <strong>Address</strong>
                <?= htmlspecialchars($currentStudent['address']) ?>
              </div>
            </div>
          </div>
          
          <div class="detail-section">
            <h4><i class="fas fa-users me-2"></i>Family Information</h4>
            <div class="detail-grid">
              <div class="detail-item">
                <strong>Parent Phone</strong>
                <?= htmlspecialchars($currentStudent['parent_phone']) ?>
              </div>
              <div class="detail-item">
                <strong>Parent Email</strong>
                <?= htmlspecialchars($currentStudent['parent_email']) ?>
              </div>
              <div class="detail-item">
                <strong>Parent/Guardian names</strong>
                <?= htmlspecialchars($currentStudent['emergency_contact']) ?>
              </div>
            </div>
          </div>
          
          <div class="detail-section">
            <h4><i class="fas fa-graduation-cap me-2"></i>Academic Information</h4>
            <div class="detail-grid">
              <div class="detail-item">
                <strong>Grade Applied For</strong>
                <?= htmlspecialchars($currentStudent['grade_applied_for']) ?>
              </div>
              <div class="detail-item">
                <strong>Previous School</strong>
                <?= htmlspecialchars($currentStudent['previous_school']) ?>
              </div>
              <div class="detail-item">
                <strong>Application Date</strong>
                <?= date('M j, Y', strtotime($currentStudent['application_date'])) ?>
              </div>
            </div>
          </div>
          
          <div class="detail-section">
            <h4><i class="fas fa-file-alt me-2"></i>Documents</h4>
            <div class="detail-grid">
              <?php if (!empty($currentStudent['student_id_proof'])): ?>
              <div class="detail-item">
                <strong>Student ID Proof</strong>
                <a href="<?= htmlspecialchars($currentStudent['student_id_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                  <i class="fas fa-eye me-1"></i> View
                </a>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($currentStudent['guardian_id_proof'])): ?>
              <div class="detail-item">
                <strong>Guardian ID Proof</strong>
                <a href="<?= htmlspecialchars($currentStudent['guardian_id_proof']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                  <i class="fas fa-eye me-1"></i> View
                </a>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($currentStudent['academic_records'])): ?>
              <div class="detail-item">
                <strong>Academic Records</strong>
                <a href="<?= htmlspecialchars($currentStudent['academic_records']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                  <i class="fas fa-eye me-1"></i> View
                </a>
              </div>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="detail-section">
            <h4><i class="fas fa-heartbeat me-2"></i>Health Information</h4>
            <div class="detail-grid">
              <div class="detail-item">
                <strong>Allergies</strong>
                <?= !empty($currentStudent['allergies']) ? htmlspecialchars($currentStudent['allergies']) : 'None reported' ?>
              </div>
            </div>
          </div>
          
          <div class="btn-group-actions mt-4">
            <?php if (($currentStudent['application_status'] ?? 'Pending') === 'Pending'): ?>
            <form method="POST" action="applications.php" class="d-inline">
              <input type="hidden" name="id_number" value="<?= $currentStudent['id_number'] ?>">
              <button type="submit" name="action" value="accept" class="btn btn-success">
                <i class="fas fa-check me-1"></i> Accept
              </button>
              <button type="submit" name="action" value="reject" class="btn btn-danger">
                <i class="fas fa-times me-1"></i> Reject
              </button>
            </form>
            <?php endif; ?>
            <a href="applications.php" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-1"></i> Back
            </a>
          </div>
        </div>
      </div>
      
    <?php else: ?>
      <!-- Default Applications List View -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-alt me-2"></i>Pending Applications</h2>
        <div>
          <a href="admin_dashboard.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
          </a>
        </div>
      </div>
      
      <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4">
          <?php echo htmlspecialchars($_GET['message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
          <?php echo htmlspecialchars($_GET['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search applications..." id="searchInput">
          </div>
        </div>
        <div class="col-md-6 text-end">
          <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="fas fa-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">All Applications</a></li>
              <li><a class="dropdown-item" href="#">Today</a></li>
              <li><a class="dropdown-item" href="#">This Week</a></li>
              <li><a class="dropdown-item" href="#">By Grade Level</a></li>
            </ul>
          </div>
        </div>
      </div>
      
      <?php
      $stmt = $conn->query("SELECT * FROM students WHERE application_status IS NULL OR application_status = 'Pending' ORDER BY application_date DESC");
      $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      if (empty($applications)): ?>
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i> No pending applications found.
        </div>
      <?php else: ?>
        <div class="applications-list">
          <?php foreach ($applications as $app): ?>
          <div class="application-card">
            <div class="application-header">
              <div>
                <h5 class="mb-0"><?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?></h5>
                <small class="text-muted">Applied for Grade <?= $app['grade_applied_for'] ?></small>
              </div>
              <span class="status-badge status-pending">
                <i class="fas fa-clock me-1"></i> Pending
              </span>
            </div>
            
            <div class="application-body">
              <div class="detail-row">
                <div class="detail-item">
                  <strong>Application Date</strong>
                  <?= date('M j, Y', strtotime($app['application_date'])) ?>
                </div>
                <div class="detail-item">
                  <strong>Student ID</strong>
                  <?= htmlspecialchars($app['id_number']) ?>
                </div>
                <div class="detail-item">
                  <strong>Date of Birth</strong>
                  <?= date('M j, Y', strtotime($app['dob'])) ?>
                </div>
                <div class="detail-item">
                  <strong>Gender</strong>
                  <?= htmlspecialchars($app['gender']) ?>
                </div>
              </div>
              
              <div class="detail-row">
                <div class="detail-item">
                  <strong>Email</strong>
                  <?= htmlspecialchars($app['email']) ?>
                </div>
                <div class="detail-item">
                  <strong>Address</strong>
                  <?= htmlspecialchars($app['address']) ?>
                </div>
                <div class="detail-item">
                  <strong>Previous School</strong>
                  <?= htmlspecialchars($app['previous_school']) ?>
                </div>
                <div class="detail-item">
                  <strong>Parent Phone</strong>
                  <?= htmlspecialchars($app['parent_phone']) ?>
                </div>
              </div>
              
              <div class="btn-group-actions mt-3">
                <form method="POST" action="applications.php" class="d-inline">
                  <input type="hidden" name="id_number" value="<?= htmlspecialchars($app['id_number']) ?>">
                  <button type="submit" name="action" value="accept" class="btn btn-success">
                    <i class="fas fa-check me-1"></i> Accept
                  </button>
                  <button type="submit" name="action" value="reject" class="btn btn-danger">
                    <i class="fas fa-times me-1"></i> Reject
                  </button>
                </form>
                <a href="applications.php?view=details&id=<?= urlencode($app['id_number']) ?>" class="btn btn-outline-primary">
                  <i class="fas fa-eye me-1"></i> View Details
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const applications = document.querySelectorAll('.application-card');
      
      applications.forEach(app => {
        const text = app.textContent.toLowerCase();
        app.style.display = text.includes(searchTerm) ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>