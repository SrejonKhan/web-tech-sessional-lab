<?php
include 'header.php';
include 'config.php';

if (isset($_COOKIE['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if username or email already exists
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="container mt-5"><p>Username or email already exists. Please choose another.</p></div>';
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Set a cookie to persist the signed-in user
            $user_id = $conn->insert_id;
            setcookie("user_id", $user_id, time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("username", $username, time() + (86400 * 30), "/"); // 86400 = 1 day
            header("Location: index.php");
            exit();
        } else {
            echo '<div class="container mt-5"><p>Error: ' . $sql . '<br>' . $conn->error . '</p></div>';
        }
    }
}

$conn->close();
?>

<div class="container mt-5">
    <h2>Sign Up</h2>
    <form method="post" action="signup.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
</div>

<?php include 'footer.php'; ?>