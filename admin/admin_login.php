<?php
session_start();
require 'con1.php';

$error = "";
$email_display = "";

// Fetch admin email hint
$query = $con->query("SELECT email FROM admin_users WHERE username = 'admin' LIMIT 1");
if ($query && $query->num_rows == 1) {
    $row = $query->fetch_assoc();
    $email_display = $row['email'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $con->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["admin_logged_in"] = true;
            $_SESSION["admin_username"] = $user["username"];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #ee828bff);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .account-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px #e04f56;
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }
            60% {
                opacity: 1;
                transform: translateY(10px);
            }
            80% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #e04f56;;
            margin-bottom: 25px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #e04f56;
            font-weight: bold;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .input-field:focus {
            border-color: #e04f56;
            box-shadow: 0 0 6px #e04f56;
            outline: none;
        }

        .btn {
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

        .btn:hover {
            background-color: #f1606cff;
            transform: scale(1.03);
        }

        .forgot-link,
        .hint-email {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .forgot-link a {
            color: #e04f56;
            text-decoration: underline;
        }

        .error-msg {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>

<div class="account-container">
    <form method="POST" class="account-form">
        <h2>Admin Login</h2>

        <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>

        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="input-field" placeholder="Enetr Username" required>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="input-field" placeholder="Enetr Password" required>
        </div>

        <div class="forgot-link">
            <a href="admin_forgetpass.php">Forgot Password?</a>
        </div>

        <br>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
