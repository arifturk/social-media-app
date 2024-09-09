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

$sql = "SELECT galleries.id, galleries.name FROM galleries WHERE galleries.user_id = '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Gallery</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Your Galleries</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='view_photos.php?gallery_id=" . $row["id"] . "'>" . $row["name"] . "</a></li>";
                }
            } else {
                echo "<li>No galleries found</li>";
            }
            ?>
        </ul>
    </body>
</html>