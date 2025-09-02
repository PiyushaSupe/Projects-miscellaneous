<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            echo "<script>window.location.href='dashboard_admin.php';</script>";
            exit;
        case 'employee':
            echo "<script>window.location.href='dashboard_employee.php';</script>";
            exit;
        case 'worker':
            echo "<script>window.location.href='dashboard_worker.php';</script>";
            exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management System</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            
        }
        .container {
            text-align: center;
            padding: 100px 20px;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #00000040;
            border: 2px solid #fff;
            border-radius: 30px;
            text-decoration: none;
            transition: 0.3s ease;
        }
        .btn:hover {
            background-color: #fff;
            color: #4facfe;
        }
        footer {
            position: fixed;
            width: 100%;
            bottom: 10px;
            text-align: center;
            font-size: 14px;
            color:black;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="mylogo.png" alt="Logo"
     style="display: block; 
            margin: 0 auto 20px auto; 
            width: 120px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            box-shadow: 0 0 10px rgba(0,0,0,0.3);">
        <h1>Inventory Management System</h1>
        
        <p>Welcome! I am <b>Piyusha</b> and this is your very first Inventory management system.
         Manage your stock, assign tasks, and streamline your workflow efficiently.<br>
        Admins can approve users, employees can manage inventory, and workers can update task statuses.</p>
        <a href="login.php" class="btn">Login</a>
        <a href="signup.php" class="btn">Sign Up</a>
        <a href="employeeinfo.xml" class="btn">Employee Info</a>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?>Piyusha Supe 23CO315 Inventory System. All rights reserved.
    </footer>
</body>
</html>
