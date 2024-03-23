<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Fetch user information
$user_id = $_SESSION["user_id"];
$query = "SELECT username, email, bio FROM user WHERE userid='$user_id'";
$result = $conn->query($query);

// Check if query execution was successful
if ($result === false) {
    // Handle error
    die("Error executing query: " . $conn->error);
}

// Check if user data exists
if ($result->num_rows > 0) {
    // Fetch user data
    $user_data = $result->fetch_assoc();
} else {
    // Handle case when user data is not found
    die("User data not found");
}

// Handle form submission for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    // Get data from the form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $bio = $_POST["bio"];

    // Update user's bio in the database
    $update_query = "UPDATE user SET username='$username', email='$email', bio='$bio' WHERE userid='$user_id'";
    $update_result = $conn->query($update_query);

    if ($update_result === false) {
        // Handle error
        $error_message = "Error updating bio: " . $conn->error;
    } else {
        // Update user_data with new bio
        $user_data["username"] = $username;
        $user_data["email"] = $email;
        $user_data["bio"] = $bio;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - Online Journaling</title>
    <link rel="stylesheet" href="../assets/css/settings.css">
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h2>Account Settings</h2>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <main>
        <section class="profile-details">
            <h2><?php echo $user_data["username"]; ?></h2>
            <p><?php echo $user_data["email"]; ?></p>
            <p><?php echo $user_data["bio"]; ?></p>
        </section>
        
        <section class="update-form">
            <h2>Update Profile</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user_data["username"]; ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user_data["email"]; ?>" required>
                
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo $user_data["bio"]; ?></textarea>
                
                <button type="submit" name="update">Update</button>
            </form>
            <?php if (isset($error_message)) : ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
        </section>
    </main>
    
</body>
</html>
