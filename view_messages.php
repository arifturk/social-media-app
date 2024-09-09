<?php
session_start();

$conn = new mysqli("localhost", "Arif", "abc123", "app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT messages.message, 
               CASE 
                   WHEN messages.sender_id = '$user_id' THEN (SELECT name FROM users WHERE id = messages.receiver_id)
                   ELSE (SELECT name FROM users WHERE id = messages.sender_id)
               END AS name
        FROM messages
        WHERE messages.sender_id = '$user_id' OR messages.receiver_id = '$user_id'
        ORDER BY messages.id DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Messages</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Messages</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><strong>" . $row["name"] . ":</strong> " . $row["message"] . "</li>";
                }
            } else {
                echo "<li>No messages found</li>";
            }
            ?>
        </ul>
    </body>
</html>