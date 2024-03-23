<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["entryid"], $_POST["shared_with_username"])) {
    $entryid = $_POST["entryid"];
    $shared_with_username = $_POST["shared_with_username"];

    // Fetch UserID of the user who wants to share the entry
    $userid = $_SESSION["user_id"];

    // Fetch UserID of the user with whom the entry will be shared
    $sql_shared_with_userid = "SELECT UserID FROM User WHERE Username = ?";
    $stmt_shared_with_userid = $conn->prepare($sql_shared_with_userid);
    $stmt_shared_with_userid->bind_param("s", $shared_with_username);
    $stmt_shared_with_userid->execute();
    $result_shared_with_userid = $stmt_shared_with_userid->get_result();

    // If user with provided username exists, proceed with sharing
    if ($result_shared_with_userid->num_rows > 0) {
        $row_shared_with_userid = $result_shared_with_userid->fetch_assoc();
        $shared_with_userid = $row_shared_with_userid["UserID"];

        // Insert share record into Shares table
        $sql_insert_share = "INSERT INTO Shares (EntryID, UserID, SharedWithUserID) VALUES (?, ?, ?)";
        $stmt_insert_share = $conn->prepare($sql_insert_share);
        $stmt_insert_share->bind_param("iii", $entryid, $userid, $shared_with_userid);
        
        if ($stmt_insert_share->execute()) {
            echo "Entry shared successfully.";
        } else {
            echo "Error sharing entry: " . $conn->error;
        }
        $stmt_insert_share->close();
    } else {
        echo "User with provided username not found.";
    }
}

// Close database connection
$conn->close();
?>
