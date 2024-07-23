<?php
include 'connection.php';

$error2 = $error3 = $error4 = $error5 = $error6 = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_id = '1';
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phoneNum = $_POST['user_phoneNum'];
    $password = $_POST['password'];
   
    // PHP validation
    if (empty($user_name)) {
        $error2 = "Name is required";
    }

    if (empty($user_email)) {
        $error3 = "Email is required";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error3 = "Invalid email format";
    }

    if (empty($password)) {
        $error4 = "Password is required";
    } elseif (strlen($password) < 8) {
        $error4 = "Password must be at least 8 characters long";
    }

    if (empty($user_phoneNum)) {
        $error5 = "Phone number is required";
    }

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["user_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES["user_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $error6 = "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $error6 = "Sorry, file already exists.";
        $uploadOk = 0;
    }
    
    // Check file size
    if ($_FILES["user_image"]["size"] > 500000) {
        $error6 = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error6 = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if (empty($error2) && empty($error3) && empty($error4) && empty($error5) && $uploadOk) {
        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
            $user_image = basename($_FILES["user_image"]["name"]);
            
            // Insert data into the users table
            $sql = "INSERT INTO users (role_id, user_image, user_name, user_email, user_phoneNum, password)
                    VALUES ('$role_id', '$user_image', '$user_name', '$user_email', '$user_phoneNum', '$password')";
            
            if ($conn->query($sql) === TRUE) {
                header('Location: signup.php');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $error6 = "Sorry, there was an error uploading your file.";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            let user_name = document.getElementById("user_name").value;
            let user_email = document.getElementById("user_email").value;
            let password = document.getElementById("password").value;
            let user_phoneNum = document.getElementById("user_phoneNum").value;
            let user_image = document.getElementById("user_image").value;

            let error = false;

            // Client-side validation
            if (user_name == "") {
                document.getElementById("nameError").innerHTML = "Name is required";
                error = true;
            } else {
                document.getElementById("nameError").innerHTML = "";
            }

            if (user_email == "") {
                document.getElementById("emailError").innerHTML = "Email is required";
                error = true;
            } else {
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(user_email)) {
                    document.getElementById("emailError").innerHTML = "Invalid email format";
                    error = true;
                } else {
                    document.getElementById("emailError").innerHTML = "";
                }
            }

            if (password == "") {
                document.getElementById("passwordError").innerHTML = "Password is required";
                error = true;
            } else if (password.length < 8) {
                document.getElementById("passwordError").innerHTML = "Password must be at least 8 characters long";
                error = true;
            } else {
                document.getElementById("passwordError").innerHTML = "";
            }

            if (user_phoneNum == "") {
                document.getElementById("phoneError").innerHTML = "Phone number is required";
                error = true;
            } else {
                document.getElementById("phoneError").innerHTML = "";
            }

            if (user_image == "") {
                document.getElementById("imageError").innerHTML = "Profile image is required";
                error = true;
            } else {
                document.getElementById("imageError").innerHTML = "";
            }

            return !error;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form action="signup.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="input-group">
                <label for="user_image">Profile Image:</label>
                <input type="file" id="user_image" name="user_image" required>
                <p id="imageError" style="color:red;"><?php echo $error6; ?></p>
            </div>
            <div class="input-group">
                <label for="user_name">Name:</label>
                <input type="text" id="user_name" name="user_name" required>
                <p id="nameError" style="color:red;"><?php echo $error2; ?></p>
            </div>
            <div class="input-group">
                <label for="user_email">Email:</label>
                <input type="email" id="user_email" name="user_email" required>
                <p id="emailError" style="color:red;"><?php echo $error3; ?></p>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <p id="passwordError" style="color:red;"><?php echo $error4; ?></p>
            </div>
            <div class="input-group">
                <label for="user_phoneNum">Phone Number:</label>
                <input type="number" id="user_phoneNum" name="user_phoneNum" required>
                <p id="phoneError" style="color:red;"><?php echo $error5; ?></p>
            </div>
            <button type="submit" class="signup">Sign Up</button>
        </form>
    </div>
</body>
</html>
