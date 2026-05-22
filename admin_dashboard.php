<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #1a73e8;
      --primary-blue-dark: #0d5bbc;
      --primary-blue-light: #e8f0fe;
      --sidebar-width: 280px;
    }
    
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
    }
    
    /* Sidebar styling */
    .sidebar {
      position: fixed;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, var(--primary-blue), #3a7bd5);
      color: white;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }
    
    .sidebar-brand {
      padding: 1.5rem 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 1rem;
    }
    
    .sidebar-brand h4 {
      margin: 0;
      font-weight: 600;
      display: flex;
      align-items: center;
    }
    
    .sidebar-brand i {
      margin-right: 10px;
      font-size: 1.5rem;
    }
    
    .sidebar-nav {
      padding: 0 1rem;
    }
    
    .nav-item {
      margin-bottom: 0.5rem;
      border-radius: 8px;
      overflow: hidden;
      transition: all 0.3s;
    }
    
    .nav-item:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateX(5px);
    }
    
    .nav-link {
      color: white;
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s;
    }
    
    .nav-link i {
      margin-right: 12px;
      width: 24px;
      text-align: center;
      font-size: 1.1rem;
    }
    
    .nav-link.active {
      background-color: rgba(255, 255, 255, 0.2);
    }
    
    /* Main content */
    .main-content {
      margin-left: var(--sidebar-width);
      padding: 2rem;
      min-height: 100vh;
    }
    
    /* Header */
    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    
    .welcome-message h2 {
      font-weight: 600;
      color: #333;
      margin-bottom: 0.5rem;
    }
    
    .welcome-message p {
      color: #666;
      margin-bottom: 0;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .user-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: var(--primary-blue);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.2rem;
    }
    
    /* Status cards */
    .status-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .status-card {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s, box-shadow 0.3s;
      border-left: 4px solid var(--primary-blue);
      display: flex;
      flex-direction: column;
    }
    
    .status-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    
    .status-card .card-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: var(--primary-blue-light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-blue);
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .status-card h3 {
      font-size: 1.8rem;
      margin: 0;
      color: #333;
    }
    
    .status-card p {
      color: #666;
      margin: 0.5rem 0 0;
    }
    
    .status-card .card-footer {
      margin-top: auto;
      padding-top: 1rem;
      font-size: 0.85rem;
      color: #888;
      border-top: 1px solid #eee;
    }
    
    /* Dashboard sections */
    .dashboard-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1.5rem;
    }
    
    .dashboard-section {
      background: white;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .section-title {
      font-weight: 600;
      color: #333;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .section-title i {
      margin-right: 10px;
      color: var(--primary-blue);
    }
    
    .section-title .view-all {
      font-size: 0.9rem;
      font-weight: normal;
      color: var(--primary-blue);
      text-decoration: none;
    }
    
    /* Applications */
    .application-item {
      padding: 1rem 0;
      border-bottom: 1px solid #eee;
    }
    
    .application-item:last-child {
      border-bottom: none;
    }
    
    .application-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }
    
    .application-name {
      font-weight: 600;
      color: #333;
    }
    
    .application-grade {
      color: #666;
      font-size: 0.9rem;
    }
    
    .application-date {
      color: #888;
      font-size: 0.85rem;
    }
    
    .application-details {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-top: 0.5rem;
    }
    
    .application-detail {
      font-size: 0.9rem;
    }
    
    .application-detail strong {
      color: #555;
    }
    
    .application-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 1rem;
    }
    
    /* Recent activity */
    .activity-item {
      padding: 0.75rem 0;
      border-bottom: 1px solid #eee;
      display: flex;
      gap: 12px;
    }
    
    .activity-item:last-child {
      border-bottom: none;
    }
    
    .activity-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: var(--primary-blue-light);
      color: var(--primary-blue);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    
    .activity-content {
      flex-grow: 1;
    }
    
    .activity-title {
      font-weight: 500;
      margin-bottom: 0.25rem;
    }
    
    .activity-time {
      color: #888;
      font-size: 0.85rem;
    }
    
    /* Quick actions */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-top: 1.5rem;
    }
    
    .quick-action {
      background: var(--primary-blue-light);
      border-radius: 8px;
      padding: 1rem;
      text-align: center;
      color: var(--primary-blue);
      text-decoration: none;
      transition: all 0.2s;
    }
    
    .quick-action:hover {
      background: var(--primary-blue);
      color: white;
    }
    
    .quick-action i {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      display: block;
    }
    
    /* Responsive adjustments */
    @media (min-width: 1600px) {
      .main-content {
        padding: 2rem 3rem;
      }
      
      .dashboard-grid {
        grid-template-columns: 3fr 1fr;
      }
    }
  </style>
</head>
<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-brand">
      <h4><i class="fas fa-school"></i> School Admin</h4>
    </div>
    <div class="sidebar-nav">
      <div class="nav-item">
        <a href="#" class="nav-link active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="applications.php" class="nav-link">
          <i class="fas fa-file-alt"></i>
          <span>Applications</span>
          <span class="badge bg-danger ms-auto"><?php echo getPendingApplicationsCount(); ?></span>
        </a>
      </div>
      <div class="nav-item">
        <a href="crazy.php" class="nav-link">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Teacher</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="enrolled_students.php" class="nav-link">
          <i class="fas fa-users"></i>
          <span>Students</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="admin_announcements.php" class="nav-link">
          <i class="fas fa-bullhorn"></i>
          <span>Announcements</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="https://www.timeanddate.com/calendar/monthly.html" class="nav-link" target="_blank">
          <i class="fas fa-calendar-alt"></i>
          <span>Calendar</span>
        </a>
      </div>
      
      <div class="nav-item mt-4">
        <a href="logout.php" class="nav-link">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
      <div class="welcome-message">
        <h2>Welcome back, Admin!</h2>
        <p id="current-date"><?php echo date('l, F j, Y'); ?></p>
      </div>
      <div class="user-profile">
        <div class="user-avatar">A</div>
        <div>
          <div class="fw-bold">Administrator</div>
          <div class="text-muted small">Super Admin</div>
        </div>
      </div>
    </div>

    <!-- Status Cards -->
    <div class="status-cards">
      <a href="enrolled_students.php" style="text-decoration: none; color: inherit;">
      <div class="status-card" >
        <div class="card-icon">
          <i class="fas fa-users"></i>
        </div>
        <h3><?php echo getStudentCount(); ?></h3>
        <p>Total Students</p>
        <div class="card-footer">
          <i class="fas fa-arrow-up text-success me-1"></i> 12% from last month
        </div>
      </div>
      </a>
      
      <a href="crazy.php" style="text-decoration: none; color: inherit;">
      <div class="status-card">
        <div class="card-icon">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <h3><?php echo getTeacherCount(); ?></h3>
        <p>Total Teachers</p>
        <div class="card-footer">
          <i class="fas fa-arrow-up text-success me-1"></i> 5% from last month
        </div>
      </div>
      </a>
      
      <a href="admin_announcements.php" style="text-decoration: none; color: inherit;">
      <div class="status-card">
        <div class="card-icon">
          <i class="fas fa-bullhorn"></i>
        </div>
        <h3><?php echo getAnnouncementCount(); ?></h3>
        <p>Announcements</p>
        <div class="card-footer">
          <i class="fas fa-arrow-down text-danger me-1"></i> 3% from last month
        </div>
      </div>
      </a>
      
      <a href="applications.php" style="text-decoration: none; color: inherit;">
      <div class="status-card">
        <div class="card-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <h3><?php echo getPendingApplicationsCount(); ?></h3>
        <p>Pending Applications</p>
        <div class="card-footer">
          <i class="fas fa-arrow-up text-success me-1"></i> 8% from last week
        </div>
      </div>
      </a>

    </div>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
      <!-- Main Content Column -->
      <div>
        <!-- Pending Applications Section -->
        <div class="dashboard-section mb-4">
          <h3 class="section-title">
            <span><i class="fas fa-clock"></i> Pending Applications</span>
            <a href="applications.php" class="view-all">View All</a>
          </h3>
          
          <div id="applicationsList">
            <?php
            require 'db_connect.php';
            $stmt = $conn->query("SELECT * FROM students WHERE application_status IS NULL OR application_status = 'Pending' ORDER BY application_date DESC LIMIT 2");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
              echo '<div class="alert alert-info">No pending applications found.</div>';
            } else {
              foreach ($results as $student):
            ?>
            <div class="application-item">
              <div class="application-header">
                <div>
                  <span class="application-name"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></span>
                  <span class="application-grade">(Grade <?= $student['grade_applied_for'] ?>)</span>
                </div>
                <div class="application-date">
                  <?= date('M d, Y', strtotime($student['application_date'])) ?>
                </div>
              </div>
              
              <div class="application-details">
                <div class="application-detail">
                  <strong>ID:</strong> <?= htmlspecialchars($student['id_number']) ?>
                </div>
                <div class="application-detail">
                  <strong>Email:</strong> <?= htmlspecialchars($student['email']) ?>
                </div>
                <div class="application-detail">
                  <strong>Address</Address>:</strong> <?= htmlspecialchars($student['address']) ?>
                </div>
                <div class="application-detail">
                  <strong>Phone:</strong> <?= htmlspecialchars($student['parent_phone']) ?>
                </div>
              </div>
              
              <div class="application-actions">
                <form method="POST" action="process_application.php" class="d-inline">
                  <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                  <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">
                    <i class="fas fa-check me-1"></i> Accept
                  </button>
                  <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">
                    <i class="fas fa-times me-1"></i> Reject
                  </button>
                  <a href="applications.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye me-1"></i> View
                  </a>
                </form>
              </div>
            </div>
            <?php
              endforeach;
            }
            ?>
          </div>
        </div>
        
        <!-- Recent Announcements Section -->
        <div class="dashboard-section">
          <h3 class="section-title">
            <span><i class="fas fa-bullhorn"></i> Recent Announcements</span>
            <a href="admin_announcements.php" class="view-all">View All</a>
          </h3>
          
          <?php
          $stmt = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC LIMIT 3");
          $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (empty($announcements)) {
            echo '<div class="alert alert-info">No announcements found.</div>';
          } else {
            foreach ($announcements as $announcement):
          ?>
          <div class="application-item">
            <div class="application-header">
              <div class="application-name"><?= htmlspecialchars($announcement['title']) ?></div>
              
            </div>
            <p class="mt-2"><?= htmlspecialchars(substr($announcement['content'], 0, 150)) ?>...</p>
            
          </div>
          <?php
            endforeach;
          }
          ?>
        </div>
      </div>
      
      <!-- Sidebar Column -->
      
        
        <!-- Quick Actions Section -->
        <div class="dashboard-section">
          <h3 class="section-title">
            <span><i class="fas fa-bolt"></i> Quick Actions</span>
          </h3>
          
          <div class="quick-actions">
            <a href="apply.html" class="quick-action">
              <i class="fas fa-user-plus"></i>
              Add Student
            </a>
            <a href="add_teacher_form.html" class="quick-action">
              <i class="fas fa-chalkboard-teacher"></i>
              Add Teacher
            </a>
            <a href="admin_announcements.php" class="quick-action">
              <i class="fas fa-bullhorn"></i>
              Post Announcement
            </a>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Update the current date (in case page is left open)
  function updateCurrentDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const now = new Date();
    document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
  }
  
  // Update date every minute (in case page is left open overnight)
  setInterval(updateCurrentDate, 60000);
  
  // Initialize charts (example - you would need to implement actual data)
  document.addEventListener('DOMContentLoaded', function() {
    // This is just a placeholder for actual chart implementation
    console.log('Charts would be initialized here with real data');
  });
</script>
</body>
</html>

<?php
// Helper functions to get counts from database
function getStudentCount() {
  require 'db_connect.php';
  $stmt = $conn->query("SELECT COUNT(*) FROM enrolled_students");
  return $stmt->fetchColumn();
}

function getTeacherCount() {
  require 'db_connect.php';
  $stmt = $conn->query("SELECT COUNT(*) FROM teachers");
  return $stmt->fetchColumn();
}

function getAnnouncementCount() {
  require 'db_connect.php';
  $stmt = $conn->query("SELECT COUNT(*) FROM announcements");
  return $stmt->fetchColumn();
}

function getPendingApplicationsCount() {
  require 'db_connect.php';
  $stmt = $conn->query("SELECT COUNT(*) FROM students WHERE application_status IS NULL OR application_status = 'Pending'");
  return $stmt->fetchColumn();
}
?>