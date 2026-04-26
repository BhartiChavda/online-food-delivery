<?php
session_start();
require 'con.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $con->prepare("SELECT * FROM admin_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE admin_users SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed_password, $email);
            if ($update->execute()) {
                $success = "✅ Password updated successfully!";
            } else {
                $error = "❌ Error updating password.";
            }
            $update->close();
        } else {
            $error = "❌ No user found with this email.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #ee828bff);
            color: var(--text-color);
            transition: background 0.5s ease; /* Smooth background transition */
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevent scrollbar from animation */
        }

        :root {
            --bg-color: #f9fafb;
            --text-color: #333;
            --container-bg: #ffffff;
            --container-shadow: 0 0 10px rgba(238, 130, 139, 0.4);
            --input-border: #ccc;
            --button-bg: #ee828bff;
            --button-hover-bg: #d66a77;
            --link-color: #ec9ba6ff;
            --success-color: green;
            --error-color: red;
        }
        
        .dark-mode {
            --bg-color: #111827;
            --text-color: #f3f4f6;
            --container-bg: #2d3748;
            --container-shadow: 0 0 15px rgba(238, 130, 139, 0.6);
            --input-border: #555;
            --button-bg: #ee828bff;
            --button-hover-bg: #d66a77;
            --link-color: #ec9ba6ff;
            --success-color: lightgreen;
            --error-color: lightcoral;
        }

        .container {
            max-width: 400px;
            width: 90%;
            padding: 30px;
            background: var(--container-bg);
            border-radius: 8px;
            box-shadow: var(--container-shadow);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            transform: translateY(0);
            opacity: 1;
            animation: fadeInScale 0.7s ease-out; /* Animation on load */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--text-color);
            transition: color 0.3s ease;
        }

        input[type="email"], input[type="password"] {
            width: calc(100% - 24px); /* Account for padding */
            padding: 12px;
            margin: 8px 0 15px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            box-sizing: border-box;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: border-color 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: var(--button-bg); /* Highlight on focus */
            box-shadow: 0 0 5px rgba(238, 130, 139, 0.5);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: var(--button-bg);
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background: var(--button-hover-bg);
            transform: translateY(-2px); /* Slight lift on hover */
        }

        input[type="submit"]:active {
            transform: translateY(0); /* Press down effect */
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            animation: slideInTop 0.5s ease-out; /* Animation for messages */
        }

        .success {
            color: var(--success-color);
            background-color: rgba(144, 238, 144, 0.2);
        }

        .error {
            color: var(--error-color);
            background-color: rgba(255, 99, 71, 0.2);
        }

        .toggle-theme {
            position: absolute;
            top: 20px;
            right: 30px;
            cursor: pointer;
            font-size: 24px;
            user-select: none;
            color: var(--text-color);
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .toggle-theme:hover {
            transform: scale(1.1);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--link-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            text-decoration: underline;
            color: var(--button-hover-bg);
        }

        /* Keyframe Animations */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes slideInTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Optional: Ripple effect for button (more advanced) */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple:after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .6s, opacity 1s;
        }
        .ripple:active:after {
            transform: scale(0, 0);
            opacity: .2;
            transition: 0s;
        }
    </style>
</head>
<body>

<div class="toggle-theme" onclick="toggleTheme()">🌙</div>

<div class="container">
    <h2>Reset Password</h2>

    <?php if ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <input type="submit" value="Reset Password" class="ripple">
    </form>

    <a href="admin_login.php">← Back to Login</a>
</div>

<script>
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    }

    window.onload = () => {
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    };
</script>

</body>
</html>