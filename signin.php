<?php
include 'header.php';
include 'config.php';

if (isset($_COOKIE['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set a cookie to persist the signed-in user
            setcookie("user_id", $row['id'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("username", $row['username'], time() + (86400 * 30), "/"); // 86400 = 1 day
            header("Location: index.php");
            exit();
        } else {
            echo '<div class="container mt-5"><p>Invalid email or password.</p></div>';
        }
    } else {
        echo '<div class="container mt-5"><p>Invalid email or password.</p></div>';
    }
}

$conn->close();
?>

<div class="container mt-5">
    <h2>Sign In</h2>
    <form method="post" action="signin.php">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
</div>

<?php include 'footer.php'; ?>