<?php
include 'header.php';
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Check if user is signed in
if (!isset($_COOKIE['user_id'])) {
    echo '<div class="container mt-5"><p>Please sign in to create a post.</p></div>';
    include 'footer.php';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_COOKIE['user_id'];
    $image = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "/uploads/";
        // $target_file = getcwd() . $target_dir . basename($_FILES["image"]["name"]);
        $target_file =   '/uploads/' . uniqid() . $_FILES["image"]['name'];
        $target_file = str_replace(' ', '_', $target_file);
        echo($target_file);
        echo('/n' . $_FILES["image"]['tmp_name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        echo($imageFileType);
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (limit to 5MB)
            if ($_FILES["image"]["size"] > 5000000) {
                echo '<div class="container mt-5"><p>Sorry, your file is too large.</p></div>';
                include 'footer.php';
                exit();
            }

            // Allow certain file formats
            $allowed_types = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowed_types)) {
                echo '<div class="container mt-5"><p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p></div>';
                include 'footer.php';
                exit();
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], __DIR__  . $target_file)) {
                $image = '.' . $target_file;
            } else {
                echo '<div class="container mt-5"><p>Sorry, there was an error uploading your file.</p></div>';
                echo $_FILES['image']['error'];
                include 'footer.php';
                exit();
            }
        } else {
            echo '<div class="container mt-5"><p>File is not an image.</p></div>';
            include 'footer.php';
            exit();
        }
    }

    $sql = "INSERT INTO posts (title, content, user_id, image) VALUES ('$title', '$content', '$user_id', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo '<div class="container mt-5"><p>Post created successfully!</p></div>';
    } else {
        echo '<div class="container mt-5"><p>Error: ' . $sql . '<br>' . $conn->error . '</p></div>';
    }
}

$conn->close();
?>

<div class="container mt-5">
    <h2>Create Post</h2>
    <form method="post" action="post.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Upload Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>

<?php include 'footer.php'; ?>