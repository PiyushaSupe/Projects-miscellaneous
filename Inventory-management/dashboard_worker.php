<?php
include 'db.php';
session_start();

// // Security check
// if (!isset($_SESSION['email']) || $_SESSION['role'] != 'worker') {
//     echo "<script>alert('Access Denied.'); window.location.href='login.php';</script>";
//     exit;
// }

// Handle task completion update
if (isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $completed_or_not = $_POST['completed_or_not'];
    $completion_datetime = ($completed_or_not == 'yes') ? date('Y-m-d H:i:s') : NULL;
    
    // Update the task status
    $conn->query("UPDATE tasks SET completed_or_not='$completed_or_not', completion_datetime='$completion_datetime' WHERE id='$task_id'");
    header("Location: dashboard_worker.php");
    exit;
}

// Fetch assigned tasks for the worker
$assigned_tasks = $conn->query("SELECT * FROM tasks WHERE assigned_to='{$_SESSION['email']}'");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Worker Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fcb69f, #ffecd2);
            margin: 0;
            padding: 0;
        }
        .container {
            width: 95%;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.2);
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4facfe;
            color: white;
        }
        form {
            margin-bottom: 40px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        select, button {
            padding: 10px;
            margin: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        .logout {
            text-align: right;
        }
        .logout a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    <h2>Worker Dashboard</h2>
    <img src="mylogo.png" alt="Logo"
     style="display: block; 
            margin: 0 auto 20px auto; 
            width: 120px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            box-shadow: 0 0 10px rgba(0,0,0,0.3);">

    <h3>Assigned Tasks</h3>
    <table>
        <tr>
            <th>ID</th><th>Task</th><th>Assigned By</th><th>Assigned Date</th><th>Status</th><th>Completion</th>
        </tr>
        <?php while ($task = $assigned_tasks->fetch_assoc()): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><?= $task['task'] ?></td>
                <td><?= $task['assigned_by'] ?></td>
                <td><?= $task['assigned_date'] ?></td>
                <td><?= ucfirst($task['completed_or_not']) ?></td>
                <td>
                    <?php if ($task['completed_or_not'] == 'no'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                            <select name="completed_or_not" required>
                                <option value="no" selected>Incomplete</option>
                                <option value="yes">Complete</option>
                            </select>
                            <button type="submit" name="update_task">Update</button>
                        </form>
                    <?php else: ?>
                        <span>Completed on <?= $task['completion_datetime'] ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
