<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Fetch journal entries for the logged-in user
$user_id = $_SESSION["user_id"];
$query = "SELECT entryid, title, content, createdat, updatedat FROM journalentry WHERE userid='$user_id'";
$result = $conn->query($query);

// Basic statistics or insights about the user's journaling activity
$total_entries = $result->num_rows;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Weblog</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h3>DASHBOARD</h3>
         <nav>
            <ul>
                <li><a href="create_entry.php">Create New Entry</a></li>
                <li><a href="settings.php">Account Settings</a></li>
                <li><a href="analytics.php">Analytics and Insights</a></li>
                <li><a href="logout.php">Logout</a></li> <!-- Add this logout link -->
            </ul>
        </nav>
    </header>
    <main>
        <section class="journal-entries">
            <h2>My Journal Entries</h2>
            <?php if ($total_entries > 0) : ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['createdat']; ?></td>
                    <td><?php echo $row['updatedat']; ?></td>
                    <td>
                        <a class="ve" href="viewing.php?entryid=<?php echo $row['entryid'];?> class="btn">View</a>
                        |
                        <a class="ve" href="editing.php?entryid=<?php echo $row['entryid']; ?>">Edit</a> 
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else : ?>
            <p>No journal entries found.</p>
            <?php endif; ?>
        </section>
        
        <section class="dashboard-stats">
            <h2>My Journaling Activity</h2>
            <p>Total Entries: <?php echo $total_entries; ?></p>
            <!-- Additional statistics or insights can be displayed here -->
        </section>

        <section class="statistics">
    <h2>Statistics</h2>
    <p>Total Entries: <?php echo $total_entries; ?></p>
    <?php
    // Fetch additional statistics from the 'journalentry' table
    $query = "SELECT COUNT(*) AS total_entries FROM journalentry WHERE userid='$user_id'";
    $result = $conn->query($query);
    if ($result === false) {
        echo "<p>Error fetching statistics: " . $conn->error . "</p>";
    } else {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<p>Total Journal Entries: " . $row['total_entries'] . "</p>";
        } else {
            echo "<p>No statistics found.</p>";
        }
    }
    ?>
</section>


<section class="latest-activity">
    <h2>Latest Activity</h2>
    <?php
    // Fetch user's latest journal entries
    $query = "SELECT title, createdat FROM journalentry WHERE userid='$user_id' ORDER BY createdat DESC LIMIT 5";
    $result = $conn->query($query);
    
    if ($result === false) {
        echo "<p>Error fetching latest activity: " . $conn->error . "</p>";
    } else {
        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['title']} - {$row['createdat']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No recent activity found.</p>";
        }
    }
    ?>
</section>

<section class="tags">
    <h2>Tags</h2>
    <?php
    // Fetch user's most frequently used tags from the database
    $tag_query = "SELECT name, COUNT(*) AS count FROM tag WHERE tagid='$user_id' GROUP BY name ORDER BY count DESC LIMIT 5";
    
    // Execute the query
    $tag_result = $conn->query($tag_query);
    
    // Check if query executed successfully
    if ($tag_result === false) {
        // Query encountered an error, print error message
        echo "<p>Error fetching tags: " . $conn->error . "</p>";
    } else {
        // Check if any tags found
        if ($tag_result->num_rows > 0) {
            // Tags found, display them
            echo "<ul>";
            while ($tag_row = $tag_result->fetch_assoc()) {
                echo "<li>{$tag_row['name']} ({$tag_row['count']})</li>";
            }
            echo "</ul>";
        } else {
            // No tags found
            echo "<p>No tags found.</p>";
        }
    }
    ?>
</section>

    </main>
    
</body>
</html>