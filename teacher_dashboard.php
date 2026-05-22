<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

try {
    // Get teacher details and subject taught
    $stmt = $conn->prepare("
        SELECT teachers.first_name, teachers.last_name, teachers.grade, subjects.subject_name
        FROM teachers
        JOIN subjects ON teachers.subject_id = subjects.subject_id
        WHERE teachers.id = :id
    ");
    $stmt->execute([':id' => $teacher_id]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        die("Teacher not found");
    }

    $subject = $teacher['subject_name'];
    $grade = $teacher['grade'];

    // Get enrolled students for this teacher's subject and grade with Accepted status
    $students_stmt = $conn->prepare("
        SELECT s.student_id, s.id_number, s.first_name, s.last_name, 
               s.grade_applied_for, s.email, s.parent_phone
        FROM students s
        INNER JOIN enrolled_students e ON s.student_id = e.student_id
        WHERE s.grade_applied_for = :grade
        AND s.application_status = 'Accepted'
        ORDER BY s.last_name, s.first_name
    ");
    $students_stmt->execute([':grade' => $grade]);
    $students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-hover: #1d4ed8;
            --light-blue: #eff6ff;
            --dark-blue: #1e40af;
            --accent-blue: #3b82f6;
            --bg-gradient: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background: var(--bg-gradient);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            border-left: 5px solid var(--primary-blue);
            transition: all 0.3s ease;
        }
        
        .dashboard-header:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            overflow: hidden;
            background-color: white;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .card-header {
            background: var(--primary-blue);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-bottom: none;
            position: relative;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-blue));
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-blue-hover);
            border-color: var(--primary-blue-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-primary {
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-outline-primary:hover {
            transform: translateY(-1px);
        }
        
        .table-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .table-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .table thead {
            background-color: var(--light-blue);
        }
        
        .table th {
            font-weight: 600;
            color: var(--dark-blue);
            padding: 1rem;
            border-bottom: 2px solid var(--accent-blue);
        }
        
        .table td {
            padding: 0.875rem 1rem;
            vertical-align: middle;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .welcome-text {
            color: var(--dark-blue);
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }
        
        .subject-info {
            background-color: var(--light-blue);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            color: var(--dark-blue);
            margin-right: 0.75rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .subject-info i {
            margin-right: 0.5rem;
            color: var(--primary-blue);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6b7280;
        }
        
        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: #dbeafe;
            background: var(--light-blue);
            width: 100px;
            height: 100px;
            line-height: 100px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .empty-state h5 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            color: #374151;
        }
        
        .empty-state p {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .container {
            max-width: 1400px;
            padding-top: 2rem;
            padding-bottom: 4rem;
        }
        
        .nav-icon {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        .badge-blue {
            background-color: var(--light-blue);
            color: var(--dark-blue);
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
            }
            
            .welcome-text {
                font-size: 1.5rem;
            }
            
            .subject-info {
                display: block;
                margin-bottom: 0.75rem;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="welcome-text">Welcome, <?php echo htmlspecialchars($teacher['first_name']) ?> <?php echo htmlspecialchars($teacher['last_name']) ?></h2>
                    <div class="d-flex flex-wrap">
                        <span class="subject-info">
                            <i class="fas fa-book"></i><?php echo htmlspecialchars($subject) ?>
                        </span>
                        <span class="subject-info">
                            <i class="fas fa-graduation-cap"></i>Grade <?php echo htmlspecialchars($grade) ?>
                        </span>
                    </div>
                </div>
                <div>
                    <a href="logout.php" class="btn btn-outline-primary">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="row">
            <!-- Input Marks Card -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i> Input Marks
                    </div>
                    <div class="card-body">
                        <?php if (empty($students)): ?>
                            <div class="empty-state">
                                <i class="fas fa-user-graduate"></i>
                                <h5>No Students</h5>
                                <p>You currently have no students in your class.</p>
                            </div>
                        <?php else: ?>
                            <form action="submit_marks.php" method="POST">
                                <input type="hidden" name="subject" value="<?php echo htmlspecialchars($subject) ?>">
                                <input type="hidden" name="grade" value="<?php echo htmlspecialchars($grade) ?>">
                                
                                <div class="mb-4">
                                    <label for="student_id" class="form-label fw-medium">Select Student</label>
                                    <select name="student_id" id="student_id" class="form-select" required>
                                        <option value="" disabled selected>Choose student...</option>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?php echo $student['student_id'] ?>">
                                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['id_number'] . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="assessment_type" class="form-label fw-medium">Assessment Type</label>
                                    <select name="assessment_type" id="assessment_type" class="form-select" required>
                                        <option value="" disabled selected>Select assessment...</option>
                                        <option value="Test 1">Test 1</option>
                                        <option value="Test 2">Test 2</option>
                                        <option value="Test 3">Test 3</option>
                                        <option value="Assignment 1">Assignment 1</option>
                                        <option value="Assignment 2">Assignment 2</option>
                                        <option value="Exam">Exam</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="marks" class="form-label fw-medium">Enter Marks (0-100)</label>
                                    <input type="number" name="marks" id="marks" class="form-control" min="0" max="100" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Marks
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Post Homework Card -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-tasks me-2"></i> Post Assessment
                    </div>
                    <div class="card-body">
                        <form action="post_homework.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="subject" value="<?php echo htmlspecialchars($subject) ?>">
                            <input type="hidden" name="grade" value="<?php echo htmlspecialchars($grade) ?>">
                            
                            <div class="mb-4">
                                <label for="title" class="form-label fw-medium">Title</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="due_date" class="form-label fw-medium">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-medium">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="pdf_file" class="form-label fw-medium">Attach PDF (optional)</label>
                                <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept="application/pdf">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" <?php echo empty($students) ? 'disabled' : '' ?>>
                                <i class="fas fa-upload me-2"></i> Post Assessment
                            </button>
                            
                            <?php if (empty($students)): ?>
                                <small class="text-muted d-block mt-3 text-center">Homework will be visible once there are accepted students in your class</small>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table Section -->
        <div class="table-container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="m-0 text-dark">
                    <i class="fas fa-users me-2 text-primary"></i> Your Students
                </h4>
                <span class="badge-blue">
                    <i class="fas fa-user-graduate me-1"></i> <?php echo count($students) ?> Students
                </span>
            </div>
            
            <?php if (empty($students)): ?>
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <h5>No Students</h5>
                    <p>You currently have no students in your class.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th><i class="fas fa-id-card me-1"></i> ID</th>
                                <th><i class="fas fa-user me-1"></i> Name</th>
                                <th><i class="fas fa-graduation-cap me-1"></i> Grade</th>
                                <th><i class="fas fa-envelope me-1"></i> Email</th>
                                <th><i class="fas fa-phone me-1"></i> Parent Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="fw-medium"><?php echo htmlspecialchars($student['id_number']) ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td>Grade <?php echo htmlspecialchars($student['grade_applied_for']) ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($student['email']) ?>" class="text-primary"><?php echo htmlspecialchars($student['email']) ?></a></td>
                                    <td><?php echo htmlspecialchars($student['parent_phone']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add subtle animations
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.dashboard-header, .card, .table-container');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
</body>
</html>