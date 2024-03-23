<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Initialize variables for form submission
$title = $content = $tags = "";
$title_err = $content_err = $tags_err = "";

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

    // Validate tags
    if (!empty(trim($_POST["tags"]))) {
        $tags = trim($_POST["tags"]);
    }

    // Check if there are no input errors
    if (empty($title_err) && empty($content_err)) {
        // Prepare an INSERT statement for journalentry table
        $sql_journalentry = "INSERT INTO journalentry (userid, title, content, createdat) VALUES (?, ?, ?, NOW())";

        if ($stmt_journalentry = $conn->prepare($sql_journalentry)) {
            // Bind variables to the prepared statement as parameters
            $stmt_journalentry->bind_param("iss", $param_userid, $param_title, $param_content);

            // Set parameters
            $param_userid = $_SESSION["user_id"];
            $param_title = $title;
            $param_content = $content;

            // Attempt to execute the prepared statement
            if ($stmt_journalentry->execute()) {
                // Entry created successfully, get the last inserted entry id
                $entry_id = $stmt_journalentry->insert_id;

                // If tags are provided, insert them into the tag table
                if (!empty($tags)) {
                    // Split tags string into an array
                    $tag_array = explode(",", $tags);

                    // Prepare an INSERT statement for tag table
                    $sql_tag = "INSERT INTO tag (name) VALUES (?)";

                    foreach ($tag_array as $tag_name) {
                        // Trim tag name and remove leading/trailing whitespace
                        $tag_name = trim($tag_name);

                        // Check if the tag name is not empty
                        if (!empty($tag_name)) {
                            // Execute the prepared statement for each tag
                            if ($stmt_tag = $conn->prepare($sql_tag)) {
                                // Bind the tag name as a parameter
                                $stmt_tag->bind_param("s", $tag_name);

                                // Attempt to execute the prepared statement
                                $stmt_tag->execute();
                            }
                        }
                    }
                }

                // Redirect user to dashboard page
                header("location: dashboard.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt_journalentry->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Entry - Online Journaling</title>
    <link rel="stylesheet" href="../assets/css/create_entry.css">
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h2>Create New Entry</h2>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <main>
        <section class="create-entry-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo $title; ?>">
                    <span class="help-block"><?php echo $title_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
                    <label>Content:</label>
                    <textarea name="content"><?php echo $content; ?></textarea>
                    <span class="help-block"><?php echo $content_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Tags:</label>
                    <input type="text" name="tags" value="<?php echo $tags; ?>" placeholder="Enter tags separated by commas">
                    <span class="help-block"><?php echo $tags_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="Save Entry">
                </div>
            </form>
        </section>
    </main>

</body>
</html>
