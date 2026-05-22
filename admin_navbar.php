<?php
// This should be included at the top of all admin pages for consistent navigation
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="admin_dashboard.php">
      <i class="fas fa-school me-2"></i>School Admin
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="admin_dashboard.php">
            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="applications.php">
            <i class="fas fa-file-alt me-1"></i> Applications
            
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="enrolled_students.php">
            <i class="fas fa-users me-1"></i> Students
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-1"></i> Admin User
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i> Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>