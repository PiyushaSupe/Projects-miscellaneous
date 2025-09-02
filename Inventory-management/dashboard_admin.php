<?php
include 'db.php';
session_start();

// if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
// {
//     echo "<script>window.location.replace('loginpage.php');</script>";
//  }


// // Security: Check if admin is logged in
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     echo "<script>alert('Access Denied.'); window.location.href='login.php';</script>";
//     exit;
// }

// Handle user status toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $toggle = $_GET['toggle'] == 'active' ? 'inactive' : 'active';
    $conn->query("UPDATE users SET status='$toggle' WHERE id=$id");
    header("Location: dashboard_admin.php");
    exit;
}

// Handle user deletion
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: dashboard_admin.php");
    exit;
}

// Handle user update
if (isset($_POST['update_user'])) {
    $id = $_POST['uid'];
    $name = $_POST['uname'];
    $email = $_POST['uemail'];
    $mob = $_POST['umob'];
    $role = $_POST['urole'];
    $status = $_POST['ustatus'];
    $conn->query("UPDATE users SET name='$name', email='$email', mob='$mob', role='$role', status='$status' WHERE id=$id");
    header("Location: dashboard_admin.php");
    exit;
}

// Handle adding new user
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mob = $_POST['mob'];
    $password = $_POST['password']; // plain text
    $role = $_POST['role'];
    $status = $_POST['status'];
    $conn->query("INSERT INTO users (name, email, mob, password, role, status) VALUES ('$name', '$email', '$mob', '$password', '$role', '$status')");
    header("Location: dashboard_admin.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #dae2f8, #d6a4a4);
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
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        table th {
            background-color: #fcb69f;
            color: white;
        }
        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 2px;
        }
        .approve { background-color: #28a745; color: white; }
        .disapprove { background-color: #dc3545; color: white; }
        .edit { background-color: #ffc107; color: black; }
        .delete { background-color: #d9534f; color: white; }
        .form-inline input, .form-inline select {
            padding: 6px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-inline button {
            padding: 6px 12px;
        }
        .logout {
            text-align: right;
            margin-bottom: 15px;
        }
        .logout a {
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
        }
        .add-user-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .add-user-form input, .add-user-form select {
            margin: 10px 5px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 180px;
        }
        .add-user-form button {
            padding: 10px 16px;
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
    <h2>Admin Dashboard - User Management</h2>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Role</th><th>Status</th><th>Actions</th>
        </tr>
        <?php while($row = $users->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?php echo $row['id']; ?><input type="hidden" name="uid" value="<?php echo $row['id']; ?>"></td>
                    <td><input type="text" name="uname" value="<?php echo $row['name']; ?>"></td>
                    <td><input type="email" name="uemail" value="<?php echo $row['email']; ?>"></td>
                    <td><input type="text" name="umob" value="<?php echo $row['mob']; ?>"></td>
                    <td>
                        <select name="urole">
                            <option <?php if($row['role']=='admin') echo "selected"; ?>>admin</option>
                            <option <?php if($row['role']=='employee') echo "selected"; ?>>employee</option>
                            <option <?php if($row['role']=='worker') echo "selected"; ?>>worker</option>
                        </select>
                    </td>
                    <td>
                        <select name="ustatus">
                            <option <?php if($row['status']=='active') echo "selected"; ?>>active</option>
                            <option <?php if($row['status']=='inactive') echo "selected"; ?>>inactive</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn approve" type="submit" name="update_user">Update</button>
                        <br><br><a class="btn edit" 
                           href="?toggle=<?php echo $row['status']; ?>&id=<?php echo $row['id']; ?>">
                           <?php echo $row['status'] == 'active' ? 'Disapprove' : 'Approve'; ?>
                        </a><br><br>
                        <a class="btn delete" href="?delete=1&id=<?php echo $row['id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Add New User</h3>
    <form class="add-user-form" method="POST">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email">
        <input type="text" name="mob" required placeholder="Mobile">
        <input type="text" name="password" required placeholder="Password">
        <select name="role" required>
            <option value="">Role</option>
            <option value="admin">Admin</option>
            <option value="employee">Employee</option>
            <option value="worker">Worker</option>
        </select>
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive" selected>Inactive</option>
        </select>
        <button type="submit" name="add_user" class="btn approve">Add User</button>
    </form>
</div>

</body>
</html>
