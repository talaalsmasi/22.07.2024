<?php
session_start();
include 'connection.php';

$error2 = $error3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $error2 = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error2 = "Invalid email format";
    }

    if (empty($password)) {
        $error3 = "Password is required";
    } elseif (strlen($password) < 8) {
        $error3 = "Password must be at least 8 characters long";
    }

    if (empty($error2) && empty($error3)) {
        $email = $conn->real_escape_string($email);

        $sql = "SELECT password, user_id, role_id,user_name FROM users WHERE user_email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            if ($password === $storedPassword) {
                if ($row['role_id'] == 1) {
                    $_SESSION['user_name'] = $row['user_name'];
                    
                    header("Location: welcome.php?id={$row['user_id']}");
                } else {
                    header("Location: admin.php?id={$row['user_id']}");
                }
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "<p style='color:red'>No user found with this email.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let emailError = "";
            let passwordError = "";

            if (email == "") {
                emailError = "Email is required";
            } else {
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    emailError = "Invalid email format";
                }
            }

            if (password == "") {
                passwordError = "Password is required";
            } else if (password.length < 8) {
                passwordError = "Password must be at least 8 characters long";
            }

            if (emailError || passwordError) {
                if (emailError) {
                    document.getElementById("emailError").innerHTML = emailError;
                } else {
                    document.getElementById("emailError").innerHTML = "";
                }

                if (passwordError) {
                    document.getElementById("passwordError").innerHTML = passwordError;
                } else {
                    document.getElementById("passwordError").innerHTML = "";
                }

                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="post" onsubmit="return validateForm()">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <p id="emailError" style="color:red"><?php echo $error2; ?></p>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <p id="passwordError" style="color:red"><?php echo $error3; ?></p>
            </div>
            <button type="submit" class="login">Login</button>
        </form>
    </div>
</body>
</html>
