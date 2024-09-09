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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gallery_id = $_POST["gallery_id"];
    $photo_name = $_FILES["photo"]["name"];
    $photo_tmp = $_FILES["photo"]["tmp_name"];

    $upload_dir = "uploads/";
    $upload_file = $upload_dir . basename($photo_name);

    if (move_uploaded_file($photo_tmp, $upload_file)) {
        $sql = "INSERT INTO photos (gallery_id, name, path) VALUES ('$gallery_id', '$photo_name', '$upload_file')";

        if ($conn->query($sql) === TRUE) {
            echo "Photo uploaded successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading photo";
    }
}

$sql = "SELECT id, name FROM galleries WHERE user_id = '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Upload Photo</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Upload Photo</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <select name="gallery_id">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No galleries found</option>";
                }
                ?>
            </select>
            <input type="file" name="photo" accept="image/*" multiple required><br><br>
            <input type="submit" value="Upload Photo">
        </form>
    </body>
</html>