
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h2>List of Clients</h2>
        <a class="btn btn-primary" href="create.php" role="button">New client</a>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>email</th>
                    <th>created date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
               include 'connection.php';
                $sql = "SELECT * FROM users";  
                $result = $conn->query($sql);
                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['user_name']}</td>
                        <td>{$row['user_email']}</td>
                        <td>{$row['user_dateCreated']}</td>
                        <td>
                            <a class='btn btn-danger btn-sm' href='view.php?id={$row['user_id']}'>View</a>
                            <a class='btn btn-primary btn-sm' href='edit.php?id={$row['user_id']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='delete.php?id={$row['user_id']}'>Delete</a>
                        </td>
                    </tr>
                    ";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
