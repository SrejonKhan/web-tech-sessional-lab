<?php
include 'header.php';
include 'config.php';

// Check if user is signed in
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];

    // Handle post deletion
    if (isset($_POST['delete_post_id'])) {
        $delete_post_id = $_POST['delete_post_id'];
        $sql = "DELETE FROM posts WHERE id='$delete_post_id' AND user_id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="container mt-5"><p>Post deleted successfully.</p></div>';
        } else {
            echo '<div class="container mt-5"><p>Error deleting post: ' . $conn->error . '</p></div>';
        }
    }

    $sql = "SELECT username, email FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        echo '<div class="container mt-5">';
        echo '<h2>Profile</h2>';
        echo '<p>Username: ' . $username . '</p>';
        echo '<p>Email: ' . $email . '</p>';
        echo '</div>';

        // Fetch posts created by the user, sorted by creation time in descending order
        $sql = "SELECT id, title, content, image, created_at FROM posts WHERE user_id='$user_id' ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<div class="container mt-5">';
            echo '<h3>Your Posts</h3>';
            while($row = $result->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["title"] . '</h5>';
                echo '<h6 class="card-subtitle mb-2 text-muted">Posted on ' . $row["created_at"] . '</h6>';
                if (!empty($row["image"])) {
                    echo '<img src="' . $row["image"] . '" class="img-fluid mb-3" alt="Post Image">';
                }
                echo '<p class="card-text">' . $row["content"] . '</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="delete_post_id" value="' . $row["id"] . '">';
                echo '<button type="submit" class="btn btn-danger">Delete</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div class="container mt-5"><p>No posts found.</p></div>';
        }
    } else {
        echo '<div class="container mt-5"><p>User not found.</p></div>';
    }
} else {
    echo '<div class="container mt-5"><p>Please sign in to view your profile.</p></div>';
}

include 'footer.php';
?>