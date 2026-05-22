<?php
require 'db_connect.php';

try {
    $stmt = $conn->query("
        SELECT 
            e.enrollment_id, e.enrollment_date,
            s.student_id, s.first_name, s.last_name, s.id_number,
            s.email, s.grade_applied_for, s.parent_phone,
            s.parent_email, s.dob, s.gender,
            s.student_id_proof, s.guardian_id_proof, s.academic_records
        FROM enrolled_students e
        JOIN students s ON e.student_id = s.student_id
        ORDER BY e.enrollment_date DESC
    ");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <title>Enrolled Students</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .table-responsive {
      min-height: 400px;
    }
    .filter-container {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .btn-group .btn {
      margin-right: 5px;
    }
    .btn-group .btn:last-child {
      margin-right: 0;
    }
    .document-btn {
      min-width: 32px;
    }
    #id_number {
      background-color: #e9ecef;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <?php include 'admin_navbar.php'; ?>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Enrolled Students</h3>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="fas fa-plus"></i> Add Student
      </button>
    </div>

    <div class="filter-container">
      <div class="row">
        <div class="col-md-6">
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search students...">
          </div>
        </div>
        <div class="col-md-6">
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="fas fa-filter"></i></span>
            <select id="gradeFilter" class="form-select">
              <option value="">All Grades</option>
              <option value="8">Grade 8</option>
              <option value="9">Grade 9</option>
              <option value="10">Grade 10</option>
              <option value="11">Grade 11</option>
              <option value="12">Grade 12</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th>ID Number</th>
            <th>Name</th>
            <th>Email</th>
            <th>Grade</th>
            <th>Gender</th>
            <th>Parent Phone</th>
            <th>Enrollment Date</th>
            <th>Documents</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="studentTableBody">
          <?php foreach ($students as $student): ?>
            <tr>
              <td><?= htmlspecialchars($student['id_number']) ?></td>
              <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
              <td><?= htmlspecialchars($student['email']) ?></td>
              <td><?= htmlspecialchars($student['grade_applied_for']) ?></td>
              <td><?= htmlspecialchars($student['gender']) ?></td>
              <td><?= htmlspecialchars($student['parent_phone']) ?></td>
              <td><?= date('M j, Y', strtotime($student['enrollment_date'])) ?></td>
              <td>
                <div class="btn-group" role="group">
                  <?php if (!empty($student['student_id_proof'])): ?>
                    <a href="download.php?file=<?= urlencode($student['student_id_proof']) ?>&type=student_id" 
                       class="btn btn-sm btn-outline-primary document-btn" title="Student ID Proof" target="_blank">
                      <i class="fas fa-id-card"></i>
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($student['guardian_id_proof'])): ?>
                    <a href="download.php?file=<?= urlencode($student['guardian_id_proof']) ?>&type=guardian_id" 
                       class="btn btn-sm btn-outline-primary document-btn" title="Guardian ID Proof" target="_blank">
                      <i class="fas fa-user-shield"></i>
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($student['academic_records'])): ?>
                    <a href="download.php?file=<?= urlencode($student['academic_records']) ?>&type=academic" 
                       class="btn btn-sm btn-outline-primary document-btn" title="Academic Records" target="_blank">
                      <i class="fas fa-file-alt"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <button class="btn btn-info btn-sm view-btn" data-id="<?= $student['student_id'] ?>">
                  <i class="fas fa-eye"></i> View
                </button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $student['student_id'] ?>">
                  <i class="fas fa-trash"></i> Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- View/Edit Modal -->
  <div class="modal fade" id="viewStudentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="updateStudentForm" onsubmit="return validateForm()">
          <div class="modal-header">
            <h5 class="modal-title">View/Edit Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="student_id" id="student_id" required>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>ID Number</label>
                <input type="text" class="form-control" name="id_number" id="id_number" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" 
                       pattern="[A-Za-z]+" title="Only letters allowed" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" 
                       pattern="[A-Za-z]+" title="Only letters allowed" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="email" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Valid email required" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Grade</label>
                <select class="form-control" name="grade_applied_for" id="grade_applied_for" required>
                  <option value="">Select Grade</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label>Gender</label>
                <select class="form-control" name="gender" id="gender" required>
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label>Parent Phone</label>
                <input 
                  type="tel" 
                  class="form-control" 
                  name="parent_phone" 
                  id="parent_phone" 
                  pattern="[0-9]{10}" 
                  maxlength="10" 
                  required 
                  title="Phone number must be exactly 10 digits (numbers only)">
              </div>
              <div class="col-md-6 mb-3">
                <label>Parent Email</label>
                <input type="email" class="form-control" name="parent_email" id="parent_email" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Valid email required" required>
              </div>
              <div class="col-md-6 mb-3">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="dob" id="dob" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update Student</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Student redirects to form -->
  <div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content text-center p-4">
        <h5>Redirect to Application Form</h5>
        <a href="apply.html" class="btn btn-success mt-3" target="_blank">Go to Form</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Filter and search functionality
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('searchInput');
      const gradeFilter = document.getElementById('gradeFilter');
      const studentTableBody = document.getElementById('studentTableBody');
      const rows = studentTableBody.getElementsByTagName('tr');
      
      function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedGrade = gradeFilter.value;
        
        for (let row of rows) {
          const name = row.cells[1].textContent.toLowerCase();
          const grade = row.cells[3].textContent;
          const shouldShow = 
            (name.includes(searchTerm) || searchTerm === '') &&
            (grade === selectedGrade || selectedGrade === '');
            
          row.style.display = shouldShow ? '' : 'none';
        }
      }
      
      searchInput.addEventListener('input', filterStudents);
      gradeFilter.addEventListener('change', filterStudents);
      
      // View/Edit functionality
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.dataset.id;
          fetch(`get_student.php?id=${id}`)
            .then(res => res.json())
            .then(data => {
              for (let key in data) {
                const el = document.getElementById(key);
                if (el) {
                  if (el.tagName === 'SELECT') {
                    // Special handling for select elements
                    el.value = data[key];
                  } else {
                    el.value = data[key];
                  }
                }
              }
              new bootstrap.Modal(document.getElementById('viewStudentModal')).show();
            });
        });
      });

      // Update student
      document.getElementById('updateStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('update_student.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.text())
        .then(response => {
          alert(response);
          location.reload();
        });
      });

      // Delete student
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          if (confirm("Are you sure you want to delete this student?")) {
            fetch(`delete_student.php`, {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id=${btn.dataset.id}`
            })
            .then(res => res.text())
            .then(response => {
              alert(response);
              location.reload();
            });
          }
        });
      });
    });

    function validateForm() {
      const firstName = document.getElementById('first_name').value;
      const lastName = document.getElementById('last_name').value;
      const grade = document.getElementById('grade_applied_for').value;
      
      // Validate names (letters only, no numbers or special characters)
      const nameRegex = /^[A-Za-z]+$/;
      
      if (!nameRegex.test(firstName)) {
        alert('First name can only contain letters (no numbers or special characters)');
        return false;
      }
      
      if (!nameRegex.test(lastName)) {
        alert('Last name can only contain letters (no numbers or special characters)');
        return false;
      }
      
      // Validate grade selection
      const allowedGrades = ['8', '9', '10', '11', '12'];
      if (!allowedGrades.includes(grade)) {
        alert('Please select a valid grade');
        return false;
      }
      
      // Validate email format
      const email = document.getElementById('email').value;
      if (!email.includes('@')) {
        alert('Please enter a valid email address');
        return false;
      }
      
      // Check all required fields
      const requiredFields = document.querySelectorAll('[required]');
      for (let field of requiredFields) {
        if (!field.value.trim()) {
          alert(`Please fill in all required fields (${field.previousElementSibling.textContent})`);
          field.focus();
          return false;
        }
      }
      
      return true;
    }
  </script>
</body>
</html>