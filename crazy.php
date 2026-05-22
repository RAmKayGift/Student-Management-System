<?php
require_once 'admin_navbar.php';
require_once 'db_connect.php'; // Your db connection file

// Define fixed grade options
$grades = ['8', '9', '10', '11', '12'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <title>Teachers Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .filter-container {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            display: none;
            font-size: 0.875em;
        }
        #editGrade {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chalkboard-teacher me-2"></i>Teachers Management</h2>
        <a href="add_teacher_form.html" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Teacher
        </a>
    </div>

    <!-- Search and Filter Section -->
    <div class="filter-container mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search teachers...">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                    <select id="gradeFilter" class="form-select">
                        <option value="">All Grades</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?= htmlspecialchars($grade) ?>"><?= htmlspecialchars($grade) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive card">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Grade</th>
                        <th>Subject</th>
                        <th>Date Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="teacherTableBody">
                    <?php
$stmt = $conn->query("SELECT t.*, s.subject_name FROM teachers t LEFT JOIN subjects s ON t.subject_id = s.subject_id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['first_name']} {$row['last_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['grade']}</td>
                            <td>{$row['subject_name']}</td>
                            <td>{$row['date_joined']}</td>
                            <td>
                                <button class='btn btn-sm btn-outline-primary view-btn' data-id='{$row['id']}'><i class='fas fa-eye'></i></button>
                                <a href='remove_teacher.php?id={$row['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Are you sure you want to delete this teacher?\")'><i class='fas fa-trash'></i></a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View/Edit Modal -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editTeacherForm" class="modal-content" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Teacher Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3 px-3">
                <input type="hidden" name="id" id="editId">
                
                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" id="editFirstName" required
                           pattern="^[A-Za-z]+$" title="Only letters are allowed, no numbers or special characters">
                    <div class="invalid-feedback">
                        Please provide a valid first name (letters only, no spaces)
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" id="editLastName" required
                           pattern="^[A-Za-z]+$" title="Only letters are allowed, no numbers or special characters">
                    <div class="invalid-feedback">
                        Please provide a valid last name (letters only, no spaces)
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" id="editEmail" required
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    <div class="invalid-feedback">
                        Please provide a valid email address
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Phone *</label>
                    <input type="tel" name="phone" class="form-control" id="editPhone" required
                           pattern="^\d{10}$" maxlength="10"
                           title="Phone number must be exactly 10 digits (e.g., 0818666961)">
                    <div class="invalid-feedback">
                        Please enter a valid 10-digit phone number (numbers only).
                    </div>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control" id="editAddress" required></textarea>
                    <div class="invalid-feedback">
                        Please provide an address
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Grade *</label>
                    <select name="grade" class="form-select" id="editGrade" required>
                        <option value="">Select Grade</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a grade
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Subject *</label>
                    <select name="subject_id" class="form-select" id="editSubject" required>
                        <option value="">Select Subject</option>
                        <!-- Options will be populated dynamically based on grade selection -->
                    </select>
                    <div class="invalid-feedback">
                        Please select a subject
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Search and Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const gradeFilter = document.getElementById('gradeFilter');
        const teacherTableBody = document.getElementById('teacherTableBody');
        const rows = teacherTableBody.getElementsByTagName('tr');
        const gradeSelect = document.getElementById('editGrade');
        const subjectSelect = document.getElementById('editSubject');

        // Define the subjects for each grade range
        const gradeSubjects = {
            '8-9': [
                {id: 1, name: 'Mathematics'},
                {id: 2, name: 'Natural Sciences'},
                {id: 3, name: 'Technology'},
                {id: 4, name: 'Economic Management Science'},
                {id: 5, name: 'English'},
                {id: 6, name: 'Tshivenda'},
                {id: 7, name: 'Creative Arts'},
                {id: 8, name: 'Social Science'},
                {id: 9, name: 'Life Orientation'}
            ],
            '10-12': [
                {id: 1, name: 'Mathematics'},
                {id: 5, name: 'English'},
                {id: 6, name: 'Tshivenda'},
                {id: 9, name: 'Life Orientation'},
                {id: 10, name: 'Physical Sciences'},
                {id: 11, name: 'Life Sciences'},
                {id: 12, name: 'Geography'}
            ]
        };

        // Function to populate subjects based on selected grade
        function populateSubjects(grade) {
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            
            let subjects = [];
            if (grade === '8' || grade === '9') {
                subjects = gradeSubjects['8-9'];
            } else if (grade === '10' || grade === '11' || grade === '12') {
                subjects = gradeSubjects['10-12'];
            }
            
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
        }

        // Event listener for grade change
        gradeSelect.addEventListener('change', function() {
            populateSubjects(this.value);
        });
        
        function filterTeachers() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedGrade = gradeFilter.value;
            
            for (let row of rows) {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const grade = row.cells[4].textContent;
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesGrade = selectedGrade === '' || grade === selectedGrade;
                
                row.style.display = matchesSearch && matchesGrade ? '' : 'none';
            }
        }
        
        searchInput.addEventListener('input', filterTeachers);
        gradeFilter.addEventListener('change', filterTeachers);
        
        // View/Edit Modal functionality
        const viewModal = new bootstrap.Modal(document.getElementById('viewTeacherModal'));
        
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const teacherId = btn.dataset.id;
                
                fetch('get_teacher.php?id=' + teacherId)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('editId').value = data.id;
                        document.getElementById('editFirstName').value = data.first_name;
                        document.getElementById('editLastName').value = data.last_name;
                        document.getElementById('editEmail').value = data.email;
                        document.getElementById('editPhone').value = data.phone;
                        document.getElementById('editAddress').value = data.address;
                        document.getElementById('editGrade').value = data.grade;
                        
                        // Populate subjects based on grade first
                        populateSubjects(data.grade);
                        
                        // Then set the subject value
                        setTimeout(() => {
                            document.getElementById('editSubject').value = data.subject_id;
                        }, 0);
                        
                        viewModal.show();
                    });
            });
        });
        
        // Form validation
        const form = document.getElementById('editTeacherForm');
        
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Validate name fields
            const firstName = document.getElementById('editFirstName');
            const lastName = document.getElementById('editLastName');
            const nameRegex = /^[A-Za-z]+$/;
            
            if (!nameRegex.test(firstName.value)) {
                firstName.classList.add('is-invalid');
                firstName.nextElementSibling.style.display = 'block';
                return;
            } else {
                firstName.classList.remove('is-invalid');
                firstName.nextElementSibling.style.display = 'none';
            }
            
            if (!nameRegex.test(lastName.value)) {
                lastName.classList.add('is-invalid');
                lastName.nextElementSibling.style.display = 'block';
                return;
            } else {
                lastName.classList.remove('is-invalid');
                lastName.nextElementSibling.style.display = 'none';
            }
            
            // Validate email
            const email = document.getElementById('editEmail');
            const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            
            if (!emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                email.nextElementSibling.style.display = 'block';
                return;
            } else {
                email.classList.remove('is-invalid');
                email.nextElementSibling.style.display = 'none';
            }
            
            // Validate grade
            const grade = document.getElementById('editGrade');
            if (!grade.value) {
                grade.classList.add('is-invalid');
                grade.nextElementSibling.style.display = 'block';
                return;
            } else {
                grade.classList.remove('is-invalid');
                grade.nextElementSibling.style.display = 'none';
            }
            
            // Validate subject
            const subject = document.getElementById('editSubject');
            if (!subject.value) {
                subject.classList.add('is-invalid');
                subject.nextElementSibling.style.display = 'block';
                return;
            } else {
                subject.classList.remove('is-invalid');
                subject.nextElementSibling.style.display = 'none';
            }
            
            // Check all required fields
            let isValid = true;
            form.querySelectorAll('[required]').forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    input.nextElementSibling.style.display = 'block';
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    input.nextElementSibling.style.display = 'none';
                }
            });
            
            if (!isValid) return;
            
            // If all valid, submit the form
            const formData = new FormData(form);
            
            fetch('update_teacher.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(response => {
                alert(response);
                viewModal.hide();
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Add real-time validation for name fields
        document.getElementById('editFirstName').addEventListener('input', function() {
            const nameRegex = /^[A-Za-z]*$/;
            if (!nameRegex.test(this.value)) {
                this.value = this.value.replace(/[^A-Za-z]/g, '');
            }
        });
        
        document.getElementById('editLastName').addEventListener('input', function() {
            const nameRegex = /^[A-Za-z]*$/;
            if (!nameRegex.test(this.value)) {
                this.value = this.value.replace(/[^A-Za-z]/g, '');
            }
        });

        document.getElementById('editPhone').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10); // Only digits, max 10
        });
    });
</script>
</body>
</html>