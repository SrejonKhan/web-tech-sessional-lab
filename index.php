<?php
include 'header.php';
include 'config.php';

// Fetch posts with usernames and creation time
$sql = "SELECT posts.id, posts.title, posts.content, posts.image, posts.created_at, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="container mt-5">';
    while($row = $result->fetch_assoc()) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row["title"] . '</h5>';
        echo '<h6 class="card-subtitle mb-2 text-muted">Posted by ' . $row["username"] . ' on ' . $row["created_at"] . '</h6>';
        if (!empty($row["image"])) {
            echo '<img src="' . $row["image"] . '" class="img-fluid mb-3" alt="Post Image">';
        }
        echo '<p class="card-text">' . $row["content"] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<div class="container mt-5"><p>No posts found</p></div>';
}

$conn->close();

include 'footer.php';
?>