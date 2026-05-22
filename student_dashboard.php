<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['id_number'])) {
    header("Location: login.html");
    exit();
}

$id_number = $_SESSION['id_number'];

// Get student information
$sql = "SELECT * FROM students WHERE id_number = :id_number";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_number' => $id_number]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Get homework (only for Accepted students, ordered by due date, and not past due)
$homework = [];
if ($student['application_status'] === 'Accepted') {
    $current_date = date('Y-m-d H:i:s');
    $sql_homework = "SELECT title, due_date, description, file_path 
                     FROM homework 
                     WHERE grade = :grade 
                     AND due_date > :current_date
                     ORDER BY due_date ASC";
    $stmt_homework = $conn->prepare($sql_homework);
    
    if (!$student) {
        die("Student not found or no data returned.");
    }

    $stmt_homework->execute([
        ':grade' => $student['grade_applied_for'],
        ':current_date' => $current_date
    ]);
    $homework = $stmt_homework->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --primary-hover: #0d5bbc;
            --sidebar-width: 250px;
            --sidebar-bg: #f8f9fa;
            --content-bg: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f5f7fa;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--sidebar-bg);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            background-color: var(--content-bg);
            min-height: 100vh;
        }
        
        .nav-link {
            color: #495057;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 5px 15px;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: #e9ecef;
            color: var(--primary-blue);
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: var(--primary-blue);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .profile-header {
            background-color: var(--primary-blue);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .badge-status {
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 20px;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
        }
        
        .content-section {
            display: none;
        }
        
        .content-section.active {
            display: block;
        }
        
        .no-homework {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <h4 class="text-primary">Student Portal</h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="showSection('subjects')">
                    <i class="fas fa-book"></i> Subjects & Marks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="showSection('homework')">
                    <i class="fas fa-tasks"></i> Assessment
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Section -->
        <div id="dashboard" class="content-section active">
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2>Welcome, <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
                        <p class="mb-0">ID: <?php echo htmlspecialchars($student['id_number']); ?></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge badge-status bg-light text-dark">
                            Status: <?php echo htmlspecialchars($student['application_status']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Date of Birth:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['dob']); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Gender:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['gender']); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Grade:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['grade_applied_for']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Address:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['address']); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Email:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['email']); ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 font-weight-bold">Parent Phone:</div>
                                <div class="col-8"><?php echo htmlspecialchars($student['parent_phone']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
// Your existing DB connection (unchanged)
$host = "localhost";
$dbname = "school_management_system";
$user = "postgres";
$password = "Fulufhelo1";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch marks data for the specific student
$student_id = $student['student_id']; // Assuming your students table has an 'id' column
try {
    $stmt = $conn->prepare("
        SELECT subject, assessment_type, marks 
        FROM marks 
        WHERE student_id = :student_id 
        ORDER BY subject, assessment_type
    ");
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $marks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $marks = []; // Empty array if query fails
    // You might want to log this error instead of silently failing
    error_log("Error fetching marks: " . $e->getMessage());
}
?>

<!-- Subjects & Marks Section -->
<div id="subjects" class="content-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary">Subjects & Marks</h3>
        <button class="btn btn-sm btn-outline-primary" onclick="exportTableToExcel()">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover" id="marksTable" style="border-collapse: separate; border-spacing: 0;">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center" style="border-top: 2px solid #343a40;">Subject</th>
                    <th class="text-center" style="border-top: 2px solid #343a40;">Assessment</th>
                    <th class="text-center" style="border-top: 2px solid #343a40;">Marks</th>
                    <th class="text-center" style="border-top: 2px solid #343a40;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($marks)): ?>
                    <?php 
                    // Group marks by subject
                    $groupedMarks = [];
                    foreach ($marks as $mark) {
                        $subject = $mark['subject'];
                        if (!isset($groupedMarks[$subject])) {
                            $groupedMarks[$subject] = [];
                        }
                        $groupedMarks[$subject][] = $mark;
                    }
                    
                    foreach ($groupedMarks as $subject => $subjectMarks): 
                        $rowspan = count($subjectMarks);
                        $firstRow = true;
                        
                        foreach ($subjectMarks as $mark): 
                            $passFail = ($mark['marks'] >= 50) ? 'Pass' : 'Fail';
                    ?>
                    <tr style="border-top: <?= $firstRow ? '3px solid #666' : '1px solid #dee2e6' ?>">
                        <?php if ($firstRow): ?>
                            <td rowspan="<?= $rowspan ?>" class="align-middle text-center font-weight-bold" 
                                style="background-color: #f8f9fa; border-right: 1px solid #dee2e6;">
                                <?= htmlspecialchars($subject) ?>
                            </td>
                        <?php endif; ?>
                        
                        <td class="text-center"><?= htmlspecialchars($mark['assessment_type']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($mark['marks']) ?></td>
                        <td class="text-center">
                            <span class="badge badge-pill" 
                                  style="background-color: <?= ($passFail == 'Pass') ? '#28a745' : '#dc3545' ?>;
                                         color: white;
                                         padding: 5px 10px;
                                         font-weight: 500;
                                         min-width: 60px;
                                         display: inline-block;">
                                <?= $passFail ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                            $firstRow = false;
                        endforeach; 
                    endforeach; 
                    ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle mr-2"></i> No marks recorded
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

        <!-- Homework Section -->
        <div id="homework" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Assessment</h3>
            </div>
            
            <?php if ($student['application_status'] !== 'Accepted'): ?>
                <div class="no-homework">
                    <i class="fas fa-lock fa-3x mb-3"></i>
                    <h4>Assessment Access Restricted</h4>
                    <p>You must have an Accepted application status to view Assessment.</p>
                </div>
            <?php elseif (empty($homework)): ?>
                <div class="no-homework">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <h4>No Current Assessment</h4>
                    <p>You have no pending Assessment assignments at this time.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($homework as $assignment): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($assignment['title']); ?></h5>
                                <span class="badge bg-light text-dark">
                                    Due: <?php echo date('M j, Y', strtotime($assignment['due_date'])); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <p><?php echo htmlspecialchars($assignment['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <?php if (!empty($assignment['file_path'])): ?>
                                        <a href="<?php echo htmlspecialchars($assignment['file_path']); ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No file attached</span>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <?php 
                                            $due_date = strtotime($assignment['due_date']);
                                            $current_date = time();
                                            $diff = $due_date - $current_date;
                                            $days_left = round($diff / (60 * 60 * 24));
                                            
                                            if ($days_left > 1) {
                                                echo $days_left . " days remaining";
                                            } elseif ($days_left == 1) {
                                                echo "Due tomorrow";
                                            } else {
                                                echo "Due today";
                                            }
                                        ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionId).classList.add('active');
            
            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
        }
        
        function exportTableToExcel() {
            const table = document.getElementById("marksTable");
            const html = table.outerHTML;
            const blob = new Blob([html], { type: "application/vnd.ms-excel" });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = "student_marks.xls";
            link.click();
        }
    </script>
</body>
</html>