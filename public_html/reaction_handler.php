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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["entryid"], $_POST["reaction"])) {
    $entryid = $_POST["entryid"];
    $userid = $_SESSION["user_id"];
    $reaction = $_POST["reaction"];

    // Insert reaction into Reactions table
    $sql_insert_reaction = "INSERT INTO Reactions (EntryID, UserID, ReactionType) VALUES (?, ?, ?)";
    $stmt_insert_reaction = $conn->prepare($sql_insert_reaction);
    $stmt_insert_reaction->bind_param("iis", $entryid, $userid, $reaction);
    
    if ($stmt_insert_reaction->execute()) {
        // Redirect back to viewing.php
        header("location: viewing.php?entryid=$entryid");
        exit;
    } else {
        echo "Error recording reaction: " . $conn->error;
    }
    $stmt_insert_reaction->close();
}

// Close database connection
$conn->close();
?>
