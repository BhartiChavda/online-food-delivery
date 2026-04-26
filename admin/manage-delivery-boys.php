<?php
include 'admin-header.php';
include '../php/config.php';

// Add Delivery Boy
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['password'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $mobile = trim($_POST['mobile']);

    $stmt = $conn->prepare("INSERT INTO delivery_boys (name,email,password,mobile) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $password, $mobile);
    if ($stmt->execute()) {
        $success = "Delivery Boy added successfully ✅";
    } else {
        $error = "Error: ".$stmt->error;
    }
}

// Fetch all delivery boys
$result = $conn->query("SELECT * FROM delivery_boys ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Delivery Boys</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        h2, h3 {
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        form input {
            flex: 1 1 45%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        form button {
            flex: 1 1 100%;
            padding: 12px;
            border: none;
            background: #4facfe;
            color: #fff;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        form button:hover {
            background: #00f2fe;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background: #4facfe;
            color: #fff;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        @media(max-width:768px) {
            form input { flex: 1 1 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Delivery Boys</h2>

    <?php if(!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <h3>Add Delivery Boy</h3>
    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="text" name="mobile" placeholder="Mobile Number" />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Add Delivery Boy</button>
    </form>

    <h3>Existing Delivery Boys</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['mobile']); ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
