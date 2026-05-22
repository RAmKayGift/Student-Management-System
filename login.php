<?php
session_start();
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $role = $_POST['role'];

    try {
        $table = "";
        $id_column = "";
        $name_column = "";

        // Set table and columns based on role
        if ($role === "admin") {
            $table = "admin";
            $id_column = "id";
            $name_column = "name";
            $email = trim($_POST['email']);
            $sql = "SELECT * FROM $table WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':email' => $email]);
        } elseif ($role === "teacher") {
            $table = "teachers";
            $id_column = "id";
            $name_column = "name";
            $email = trim($_POST['email']);
            $sql = "SELECT * FROM $table WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':email' => $email]);
        } elseif ($role === "student") {
            $table = "students";
            $id_column = "id_number";
            $name_column = "first_name";
            $id_number = trim($_POST['id_number']);
            $sql = "SELECT * FROM $table WHERE id_number = :id_number";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id_number' => $id_number]);
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Set session variables based on role
                if ($role === "admin") {
                    $_SESSION['admin_id'] = $user[$id_column];
                    $_SESSION['admin_name'] = $user[$name_column];
                    header("Location: admin_dashboard.php");
                } elseif ($role === "teacher") {
                    $_SESSION['teacher_id'] = $user[$id_column];
                    $_SESSION['teacher_name'] = $user[$name_column];
                    header("Location: teacher_dashboard.php");
                } elseif ($role === "student") {
                    $_SESSION['id_number'] = $user['id_number'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['student_name'] = $user['first_name'] . " " . $user['surname'];
                    header("Location: student_dashboard.php");
                }
                exit();
            } else {
                $error = "Wrong password.";
            }
        } else {
            $error = ($role === "student") ? "ID number not found." : "Email not found.";
        }

    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/favicon.png" type="image/png">
  <title>Login - Student Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #4361ee;
      --primary-hover: #3a56d4;
      --light-blue: #f0f5ff;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
      max-width: 420px;
      margin: 0 auto;
      margin-top: 5vh;
    }
    
    .login-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    
    .login-card:hover {
      transform: translateY(-5px);
    }
    
    .card-header {
      background-color: var(--primary-blue);
      color: white;
      text-align: center;
      padding: 20px;
      border-bottom: none;
    }
    
    .card-body {
      padding: 30px;
    }
    
    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #e0e0e0;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary {
      background-color: var(--primary-blue);
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s;
    }
    
    .btn-primary:hover {
      background-color: var(--primary-hover);
      transform: translateY(-2px);
    }
    
    .btn-outline-primary {
      color: var(--primary-blue);
      border-color: var(--primary-blue);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-blue);
      color: white;
    }
    
    .forgot-password {
      color: #6c757d;
      transition: color 0.3s;
    }
    
    .forgot-password:hover {
      color: var(--primary-blue);
      text-decoration: none;
    }
    
    .input-icon {
      position: relative;
    }
    
    .input-icon i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }
    
    .input-icon input {
      padding-left: 40px;
    }
    
    .nav-buttons {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    
    .alert-danger {
      border-radius: 8px;
      background-color: #f8d7da;
      color: #721c24;
      border-color: #f5c6cb;
    }
  </style>
</head>
<body>
  <div class="container login-container">
    <div class="nav-buttons">
      <a href="index.php" class="btn btn-outline-primary"><i class="fas fa-home"></i> Home</a>
      <a href="apply.html" class="btn btn-primary"><i class="fas fa-user-plus"></i> Apply</a>
    </div>
    
    <div class="card login-card">
      <div class="card-header">
        <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Welcome Back</h3>
      </div>
      <div class="card-body">
        <?php if (!empty($error)) : ?>
          <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">

         <div class="mb-4">
            <label for="role" class="form-label">Login as</label>
            <select name="role" id="roleSelect" required class="form-control">
              <option value="student" <?php if (isset($role) && $role == 'student') echo 'selected'; ?>>Student</option>
              <option value="teacher" <?php if (isset($role) && $role == 'teacher') echo 'selected'; ?>>Teacher</option>
              <option value="admin" <?php if (isset($role) && $role == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
          </div>

          <div class="mb-3 input-icon" id="emailField">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" class="form-control" 
                   placeholder="Email Address" value="<?php echo (isset($email) && ($_POST['role'] ?? '') !== 'student' ? htmlspecialchars($email) : ''); ?>">
          </div>
          
          <div class="mb-3 input-icon" id="idNumberField" style="display: none;">
            <i class="fas fa-id-card"></i>
            <input type="text" name="id_number" id="id_number" class="form-control" 
                   placeholder="ID Number" value="<?php echo (isset($id_number) && ($_POST['role'] ?? '') === 'student' ? htmlspecialchars($id_number) : ''); ?>">
          </div>
          
          <div class="mb-3 input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" required class="form-control" placeholder="Password">
          </div>
          
          <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-sign-in-alt"></i> Login
          </button>
          
          <div class="text-center">
            <a href="forgot-password.html" class="forgot-password">Forgot Password?</a>
          </div>
        </form>
      </div>
    </div>
    
    <div class="text-center mt-4">
      <p class="text-muted">Don't have an account? <a href="apply.html" class="text-primary">Sign up</a></p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('roleSelect').addEventListener('change', function() {
      const role = this.value;
      const emailField = document.getElementById('emailField');
      const idNumberField = document.getElementById('idNumberField');
      
      if (role === 'student') {
        emailField.style.display = 'none';
        idNumberField.style.display = 'block';
        document.getElementById('email').removeAttribute('required');
        document.getElementById('id_number').setAttribute('required', '');
      } else {
        emailField.style.display = 'block';
        idNumberField.style.display = 'none';
        document.getElementById('email').setAttribute('required', '');
        document.getElementById('id_number').removeAttribute('required');
      }
    });

    // Initialize the correct field based on selected role
    document.addEventListener('DOMContentLoaded', function() {
      const roleSelect = document.getElementById('roleSelect');
      roleSelect.dispatchEvent(new Event('change'));
    });
  </script>
</body>
</html>