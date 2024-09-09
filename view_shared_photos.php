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

$sql = "SELECT shared_photos.id, shared_photos.path FROM shared_photos WHERE shared_photos.receiver_id = '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Shared Photos</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Shared Photos</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><img src='" . $row["path"] . "' alt='Shared Photo' width='200'><br>";
                    echo "<a href='download_shared_photo.php?photo_id=" . $row["id"] . "'>Download</a></li>";
                }
            } else {
                echo "<li>No shared photos found</li>";
            }
            ?>
        </ul>
    </body>
</html>