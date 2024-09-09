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

$sql = "SELECT users.name
        FROM users
        JOIN friend_requests ON (users.id = friend_requests.sender_id OR users.id = friend_requests.receiver_id)
        WHERE (friend_requests.sender_id = '$user_id' OR friend_requests.receiver_id = '$user_id') 
        AND friend_requests.status = 'accepted'
        AND users.id != '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>List of Friends</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a><br><br>
        <h2>List of Friends</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . $row["name"] . "</li>";
                }
            } else {
                echo "<li>No friends found</li>";
            }
            ?>
        </ul>
    </body>
</html>
