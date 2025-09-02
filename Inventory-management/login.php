<?php
session_start();
include 'db.php'; // database connection

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['status'] === 'inactive') {
            $msg = "Your account is not approved by the admin yet.";
        } elseif ($password == $user['password']) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // JS redirect based on role
            if ($user['role'] == 'admin') {
                echo "<script>window.location.href='dashboard_admin.php';</script>";
            } elseif ($user['role'] == 'employee') {
                echo "<script>window.location.href='dashboard_employee.php';</script>";
            } elseif ($user['role'] == 'worker') {
                echo "<script>window.location.href='dashboard_worker.php';</script>";
            }
            exit;
        } else {
            $msg = "Incorrect password.";
        }
    } else {
        $msg = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Inventory System</title>
    <style>
    body {
        background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-box {
        background-color: #ffffffdd;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        text-align: center;
        width: 100%;
        max-width: 400px;
    }

    h2 {
        margin-bottom: 25px;
        color: #333;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin: 15px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        background-color: #4facfe;
        border: none;
        color: white;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 10px;
    }

    button:hover {
        background-color: #00c3ff;
    }

    .msg {
        color: red;
        margin-top: 10px;
        font-size: 15px;
    }

    .back-link {
        margin-top: 20px;
        display: block;
        color: #555;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>

<div class="login-box">
<img src="mylogo.png" alt="Logo"
     style="display: block; 
            margin: 0 auto 20px auto; 
            width: 120px; 
            height: 150px; 
            border-radius: 50%; 
            object-fit: cover; 
            box-shadow: 0 0 10px rgba(0,0,0,0.3);">
    <h2>User Login</h2>
    <form method="POST">
        <input type="email" name="email" required placeholder="Email"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit">Login</button>
        <div class="msg"><?php echo $msg; ?></div>
    </form>
    <a href="index.php" class="back-link">← Back to Home</a>
</div>

</body>
</html>
