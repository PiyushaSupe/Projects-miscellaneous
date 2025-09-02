<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mob = trim($_POST['mob']);
    $password = trim($_POST['password']);  // Password is stored as plain text
    $role = $_POST['role'];
    $status = "inactive";

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $msg = "User already registered with this email.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, mob, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $mob, $password, $role, $status);
        if ($stmt->execute()) {
            $msg = "Registration successful! Await admin approval.";
        } else {
            $msg = "Error in registration.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Inventory System</title>
    <style>
        body {
            background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .signup-box {
            background-color: #ffffffee;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background-color: #f77a52;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #fb5a36;
        }

        .msg {
            color: green;
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

<div class="signup-box">
    <h2>User Registration</h2>
    <form method="POST">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="email" name="email" required placeholder="Email">
        <input type="text" name="mob" required placeholder="Mobile Number">
        <input type="password" name="password" required placeholder="Password">
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="employee">Employee</option>
            <option value="worker">Worker</option>
        </select>
        <button type="submit">Register</button>
        <div class="msg"><?php echo $msg; ?></div>
    </form>
    <a href="index.php" class="back-link">← Back to Home</a>
</div>

</body>
</html>
