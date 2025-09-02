<?php
include 'db.php';
session_start();

// // Security check
// if (!isset($_SESSION['email']) || $_SESSION['role'] != 'employee') {
//     echo "<script>alert('Access Denied.'); window.location.href='login.php';</script>";
//     exit;
// }

// Handle inventory addition
if (isset($_POST['add_inventory'])) {
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $date = date('Y-m-d');
    $status = $_POST['status'];
    $conn->query("INSERT INTO inventory (item_name, quantity, date_stocked_at, status) VALUES ('$item', '$quantity', '$date', '$status')");
    header("Location: dashboard_employee.php");
    exit;
}

// Handle task assignment
if (isset($_POST['assign_task'])) {
    $task = $_POST['task'];
    $assigned_to = $_POST['assigned_to'];
    $assigned_by = $_SESSION['email'];
    $assigned_date = date('Y-m-d');
    $conn->query("INSERT INTO tasks (task, assigned_to, assigned_by, assigned_date, completed_or_not, completion_datetime) VALUES ('$task', '$assigned_to', '$assigned_by', '$assigned_date', 'no', NULL)");
    header("Location: dashboard_employee.php");
    exit;
}

// Fetch inventory and workers
$inventory = $conn->query("SELECT * FROM inventory");
$workers = $conn->query("SELECT email FROM users WHERE role='worker' AND status='active'");
$tasks = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
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
            background-color: #ff7e5f;
            color: white;
        }
        form {
            margin-bottom: 40px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        input, select, button {
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
    <img src="mylogo.png" alt="Logo"
     style="display: block; 
            margin: 0 auto 20px auto; 
            width: 120px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            box-shadow: 0 0 10px rgba(0,0,0,0.3);">
    <h2>Employee Dashboard</h2>

    <h3>Add Inventory Item</h3>
    <form method="POST">
        <input type="text" name="item" required placeholder="Item Name">
        <input type="number" name="quantity" required placeholder="Quantity">
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="alright">Alright</option>
            <option value="warning">Warning</option>
            <option value="danger">Danger</option>
        </select>
        <button type="submit" name="add_inventory">Add Inventory</button>
    </form>

    <h3>Current Inventory</h3>
    <table>
        <tr>
            <th>ID</th><th>Item</th><th>Quantity</th><th>Date Stocked</th><th>Status</th>
        </tr>
        <?php while ($row = $inventory->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['item_name'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['date_stocked_at'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Assign Task to Worker</h3>
    <form method="POST">
        <input type="text" name="task" required placeholder="Task Description">
        <select name="assigned_to" required>
            <option value="">Select Worker</option>
            <?php while($w = $workers->fetch_assoc()): ?>
                <option value="<?= $w['email'] ?>"><?= $w['email'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="assign_task">Assign Task</button>
    </form>

    <h3>Assigned Tasks</h3>
    <table>
        <tr>
            <th>ID</th><th>Task</th><th>Assigned To</th><th>Assigned By</th><th>Assigned Date</th><th>Status</th><th>Completed On</th>
        </tr>
        <?php while ($task = $tasks->fetch_assoc()): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><?= $task['task'] ?></td>
                <td><?= $task['assigned_to'] ?></td>
                <td><?= $task['assigned_by'] ?></td>
                <td><?= $task['assigned_date'] ?></td>
                <td><?= ucfirst($task['completed_or_not']) ?></td>
                <td><?= $task['completion_datetime'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
