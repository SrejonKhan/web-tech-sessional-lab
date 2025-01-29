<?php
// Clear the user_id and username cookies
setcookie("user_id", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");

// Redirect to home page
header("Location: index.php");
exit();