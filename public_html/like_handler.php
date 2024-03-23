<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Check if entry ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["entryid"])) {
    $entryid = $_POST["entryid"];
    $userid = $_SESSION["user_id"];

    // Prepare and execute the query to insert a like
    $sql = "INSERT INTO Likes (EntryID, UserID) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $entryid, $userid);
        if ($stmt->execute()) {
            // Redirect back to the viewing page
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
