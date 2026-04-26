<?php
session_start();
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['name'];

    $con = new mysqli("localhost", "root", "", "login_db");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $stmt = $con->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (isset($user['email']) && $user['email'] === $email) {
            $_SESSION['verified_email'] = $email;
            $success = "✅ Account verified. <a href='update_admin_password.php'>Click here to reset your password</a>.";
        } else {
            $error = "❌ Invalid credentials.";
        }
    } else {
        $error = "❌ No such user found.";
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #ee828bff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #ee828bff;
            margin-bottom: 25px;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
            transition: 0.3s;
        }

        .input-field:focus {
            outline: none;
            border-color: #ee828bff;
            box-shadow: 0 0 8px #ee828bff;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #ee828bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #f1606cff;
            transform: scale(1.03);
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        a {
            color: #ee828bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .animated {
            animation-duration: 1s;
            animation-fill-mode: both;
        }

        .fadeInDown {
            animation-name: fadeInDown;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -30%, 0);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container animated fadeInDown">
    <h2>Forgot Password</h2>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="email" class="input-field" placeholder="Enter admin email" required>
        <input type="text" name="name" class="input-field" placeholder="Enter admin username" required>
        <button type="submit" class="login-btn">🔒 Verify</button>
    </form>

    <a class="back-link" href="admin_login.php">← Back to Login</a>
</div>

</body>
</html>
