<?php
include 'db.php';  // Your DB connection

$sql = "SELECT * FROM teachers ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($teachers as $teacher) {
    echo "<tr>";
    echo "<td>{$teacher['id']}</td>";
    echo "<td>{$teacher['first_name']} {$teacher['last_name']}</td>";
    echo "<td>{$teacher['email']}</td>";
    echo "<td>{$teacher['phone']}</td>";
    echo "<td>{$teacher['subject_id']}</td>"; // You can join with a subjects table later
    echo "<td>{$teacher['grade']}</td>";
    echo "<td>{$teacher['date_joined']}</td>";
    echo "<td>
            <form method='POST' action='remove_teacher.php' onsubmit='return confirm(\"Are you sure?\")'>
                <input type='hidden' name='id' value='{$teacher['id']}'>
                <button class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></button>
            </form>
          </td>";
    echo "</tr>";
}
?>
