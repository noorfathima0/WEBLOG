<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Initialize variables
$entryid = $title = $content = $createdat = $updatedat = $like_count = 0;
$liked = false;

// Check if entry ID is provided in the URL
if (isset($_GET["entryid"])) {
    $entryid = $_GET["entryid"];

    // Prepare a SELECT statement to retrieve entry details
    $sql = "SELECT entryid, title, content, createdat, updatedat FROM journalentry WHERE entryid = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $entryid);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $stmt->store_result();

            // Check if entry exists
            if ($stmt->num_rows == 1) {
                // Bind result variables
                $stmt->bind_result($entryid, $title, $content, $createdat, $updatedat);

                // Fetch values
                $stmt->fetch();
            } else {
                // Entry does not exist, redirect to dashboard
                header("location: dashboard.php");
                exit;
            }
        } else {
            echo "Error executing SQL query: " . $conn->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    // Check if user has already liked the entry
    $userid = $_SESSION["user_id"];
    $sql_like = "SELECT COUNT(*) FROM `Likes` WHERE EntryID = ? AND UserID = ?";
    if ($stmt_like = $conn->prepare($sql_like)) {
        // Bind variables to the prepared statement as parameters
        $stmt_like->bind_param("ii", $entryid, $userid);

        // Attempt to execute the prepared statement
        if ($stmt_like->execute()) {
            // Store result
            $stmt_like->bind_result($like_count);
            $stmt_like->fetch();

            // Check if user has already liked the entry
            if ($like_count > 0) {
                $liked = true;
            }
        } else {
            echo "Error executing SQL query: " . $conn->error;
        }

        // Close statement
        $stmt_like->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }
}

// Handle like/unlike logic when like button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["like"])) {
    if (!$liked) {
        // User hasn't liked the entry, insert like
        $sql_insert_like = "INSERT INTO `Likes` (EntryID, UserID) VALUES (?, ?)";
        if ($stmt_insert_like = $conn->prepare($sql_insert_like)) {
            // Bind variables to the prepared statement as parameters
            $stmt_insert_like->bind_param("ii", $entryid, $userid);

            // Attempt to execute the prepared statement
            if ($stmt_insert_like->execute()) {
                // Increment like count
                $liked = true;
                $like_count++;
            } else {
                echo "Error executing SQL query: " . $conn->error;
            }

            // Close statement
            $stmt_insert_like->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    } else {
        // User has already liked the entry, unlike it
        $sql_delete_like = "DELETE FROM `Likes` WHERE EntryID = ? AND UserID = ?";
        if ($stmt_delete_like = $conn->prepare($sql_delete_like)) {
            // Bind variables to the prepared statement as parameters
            $stmt_delete_like->bind_param("ii", $entryid, $userid);

            // Attempt to execute the prepared statement
            if ($stmt_delete_like->execute()) {
                // Decrement like count
                $liked = false;
                $like_count--;
            } else {
                echo "Error executing SQL query: " . $conn->error;
            }

            // Close statement
            $stmt_delete_like->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Entry - Online Journaling</title>
    <link rel="stylesheet" href="../assets/css/viewing.css">
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h2>View Entry</h2>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <main>
        <section class="entry-details">
            <h2>Title: <?php echo htmlspecialchars($title); ?></h2>
            <p><strong>Created Date:</strong> <?php echo htmlspecialchars($createdat); ?></p>
            <p><strong>Last Updated On:</strong> <?php echo htmlspecialchars($updatedat); ?></p>
            <p><strong>Content:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
            <p><strong>Comments:</strong></p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?entryid=" . $entryid; ?>" method="post">
                <input type="hidden" name="like" value="<?php echo $liked ? 'unlike' : 'like'; ?>">
                <button type="submit" class="btn"><?php echo $liked ? 'Unlike' : 'Like'; ?></button>
            </form>
            <p><strong>Likes:</strong> <?php echo $like_count; ?></p>
        </section>

        <section class="comments">
        <h2>Comments</h2>
        <!-- PHP code to display comments goes here -->
        <?php

// Fetch comments for the viewed entry
$sql_comments = "SELECT UserID, Comment, CreatedAt FROM Comments WHERE EntryID = ?";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $entryid);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

// Display comments
while ($row_comment = $result_comments->fetch_assoc()) {
    echo "<div class='comment'>";
    echo "<p><strong>Comment:</strong> " . $row_comment['Comment'] . "</p>";
    echo "<p><strong>Created At:</strong> " . $row_comment['CreatedAt'] . "</p>";
    echo "</div>";
}
$stmt_comments->close();
?>

    </section>

    <!-- Form to allow users to share the entry -->
<form action="share_handler.php" method="post">
    <input type="hidden" name="entryid" value="<?php echo $entryid; ?>">
    <input type="text" name="shared_with_username" placeholder="Enter username to share with">
    <input type="submit" value="Share">
</form><br>

<!-- Form to allow users to react to the entry -->
<form action="reaction_handler.php" method="post">
    <input type="hidden" name="entryid" value="<?php echo $entryid; ?>">
    <button type="submit" name="reaction" value="Haha">Haha</button>
    <button type="submit" name="reaction" value="Love">Love</button>
    <button type="submit" name="reaction" value="Wow">Wow</button>
    <button type="submit" name="reaction" value="Sad">Sad</button>
    <button type="submit" name="reaction" value="Angry">Angry</button>
    <!-- Add more buttons for other reaction types as needed -->
</form>

<section class="reaction">
    <?php
    // Fetch reactions for the viewed entry
$sql_reactions = "SELECT UserID, ReactionType FROM Reactions WHERE EntryID = ?";
$stmt_reactions = $conn->prepare($sql_reactions);
$stmt_reactions->bind_param("i", $entryid);
$stmt_reactions->execute();
$result_reactions = $stmt_reactions->get_result();

// Display reactions
echo "<h3>Reactions:</h3>";
while ($row_reaction = $result_reactions->fetch_assoc()) {
    echo "<p>User " . $row_reaction['UserID'] . " reacted with: " . $row_reaction['ReactionType'] . "</p>";
}
?>
</section>

    </main>
   
</body>
</html>
