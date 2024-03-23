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
$title = $content = $entryid = "";
$title_err = $content_err = "";

// Check if entry ID is provided in the URL
if (isset($_GET["entryid"])) {
    $entryid = $_GET["entryid"];

    // Prepare a SELECT statement to retrieve entry details
    $sql = "SELECT title, content FROM journalentry WHERE entryid = ?";
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
                $stmt->bind_result($title, $content);

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
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate content
    if (empty(trim($_POST["content"]))) {
        $content_err = "Please enter content for your entry.";
    } else {
        $content = trim($_POST["content"]);
    }

    // Check if there are no input errors
    if (empty($title_err) && empty($content_err)) {
        // Prepare an UPDATE statement for journalentry table
        $sql_update = "UPDATE journalentry SET title = ?, content = ?, updatedat = NOW() WHERE entryid = ?";

        if ($stmt_update = $conn->prepare($sql_update)) {
            // Bind variables to the prepared statement as parameters
            $stmt_update->bind_param("ssi", $param_title, $param_content, $param_entryid);

            // Set parameters
            $param_title = $title;
            $param_content = $content;
            $param_entryid = $entryid;

            // Attempt to execute the prepared statement
            if ($stmt_update->execute()) {
                // Redirect to dashboard after updating
                header("location: dashboard.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_update->close();
        }
    }
}


    // Prepare a SELECT statement to retrieve entry details and likes count
    $sql = "SELECT je.title, je.content, je.createdat, je.updatedat, COUNT(l.LikeID) AS likes_count
            FROM journalentry je
            LEFT JOIN likes l ON je.entryid = l.entryid
            WHERE je.entryid = ?
            GROUP BY je.entryid";
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
                $stmt->bind_result($title, $content, $createdat, $updatedat, $likes_count);

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



// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Entry - Online Journaling</title>
    <link rel="stylesheet" href="../assets/css/editing.css">
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h2>Edit Entry</h2>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <main>
        <section class="edit-entry-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?entryid=<?php echo htmlspecialchars($entryid); ?>" method="post">
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    <span class="help-block"><?php echo $title_err; ?></span>
                </div><br>
                <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
                    <label>Content:</label>
                    <textarea name="content"><?php echo htmlspecialchars($content); ?></textarea>
                    <span class="help-block"><?php echo $content_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="Save Changes">
                </div>
            </form>
        </section>

        <section class="entry-details">
            <h2>Title: <?php echo htmlspecialchars($title); ?></h2>
            <p><strong>Created Date:</strong> <?php echo htmlspecialchars($createdat); ?></p>
            <p><strong>Last Updated On:</strong> <?php echo htmlspecialchars($updatedat); ?></p>
            <p><strong>Content:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
            <p><strong>Likes:</strong> <?php echo $likes_count; ?></p>
        </section>
        <section class="entry-actions">
            <!-- Add like button here -->
            <form action="like_handler.php" method="post">
                <input type="hidden" name="entryid" value="<?php echo $entryid; ?>">
                <input type="submit" name="like" value="Like">
            </form>
        </section><br>

        <!-- Form to allow users to add new comments -->
<form action="comment_handler.php" method="post" class="comment">
    <input type="hidden" name="entryid" value="<?php echo $entryid; ?>">
    <textarea name="comment" placeholder="Add a comment"></textarea>
    <input type="submit" value="Post Comment">
</form>
    </main>

</body>
</html>