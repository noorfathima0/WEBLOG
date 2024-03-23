<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Check if entry ID and comment are provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["entryid"], $_POST["comment"])) {
    $entryid = $_POST["entryid"];
    $userid = $_SESSION["user_id"];
    $comment = $_POST["comment"];

    // Prepare and execute the query to insert a new comment
    $sql = "INSERT INTO Comments (EntryID, UserID, Comment) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $entryid, $userid, $comment);
        if ($stmt->execute()) {
            // Redirect back to the viewing page after posting the comment
            header("location: viewing.php?entryid=$entryid");
            exit;
        } else {
            echo "Error executing query: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>
