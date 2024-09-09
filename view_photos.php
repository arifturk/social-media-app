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
$gallery_id = $_GET["gallery_id"];

$sql = "SELECT photos.id, photos.name, photos.path
        FROM photos
        JOIN galleries ON photos.gallery_id = galleries.id
        WHERE galleries.user_id = '$user_id' AND galleries.id = '$gallery_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Photos</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Gallery Photos</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li><img src='" . $row["path"] . "' alt='" . $row["name"] . "' width='200'><br>";
                    echo "<a href='download_photo.php?photo_id=" . $row["id"] . "'>Download</a></li>";
                }
            } else {
                echo "<li>No photos found</li>";
            }
            ?>
        </ul>
    </body>
</html>