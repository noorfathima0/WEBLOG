<?php
session_start();
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../include/db.php";

// Fetch user ID
$user_id = $_SESSION["user_id"];

// Query to fetch total number of entries
$query_total_entries = "SELECT COUNT(*) AS total_entries FROM journalentry WHERE userid='$user_id'";
$result_total_entries = $conn->query($query_total_entries);
$total_entries = ($result_total_entries) ? $result_total_entries->fetch_assoc()['total_entries'] : 0;

// Query to fetch average entry length
$query_avg_entry_length = "SELECT AVG(LENGTH(content)) AS avg_entry_length FROM journalentry WHERE userid='$user_id'";
$result_avg_entry_length = $conn->query($query_avg_entry_length);
$avg_entry_length = ($result_avg_entry_length) ? $result_avg_entry_length->fetch_assoc()['avg_entry_length'] : 0;

// Query to fetch most used tags
$query_most_used_tags = "SELECT tagid, name, COUNT(*) AS tag_count FROM tag GROUP BY tagid, name ORDER BY tag_count DESC LIMIT 5";
$result_most_used_tags = $conn->query($query_most_used_tags);
$most_used_tags = ($result_most_used_tags) ? $result_most_used_tags->fetch_all(MYSQLI_ASSOC) : [];

// Query to fetch all tags and their counts
$query_all_tags = "SELECT tagid, name, COUNT(*) AS tag_count FROM tag GROUP BY tagid, name ORDER BY tag_count DESC";
$result_all_tags = $conn->query($query_all_tags);
$all_tags = ($result_all_tags) ? $result_all_tags->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics and Insights - Online Journaling</title>
    <link rel="stylesheet" href="../assets/css/analytics.css">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>WEBLOG</h1>
        <h2>Analytics and Insights</h2>
        <a href="dashboard.php">Dashboard</a>
    </header>
    <main>
        <section class="analytics">
            <h2>Statistics</h2>
            <ul>
                <li>Total Entries: <?php echo $total_entries; ?></li>
                <li>Average Entry Length: <?php echo round($avg_entry_length, 2); ?> characters</li>
                <!-- Add more statistics as needed -->
            </ul>
        </section>
        
        <section class="insights">
            <h2>Insights</h2>
            <table>
                <tr>
                    <th>Tag</th>
                    <th>Count</th>
                </tr>
                <?php foreach ($all_tags as $tag) : ?>
                    <tr>
                        <td><?php echo $tag['name']; ?></td>
                        <td><?php echo $tag['tag_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
        
    </main>
</body>
</html>
