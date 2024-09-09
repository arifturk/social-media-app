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
    $gallery_name = $_POST["gallery_name"];

    $sql = "INSERT INTO galleries (user_id, name) VALUES ('$user_id', '$gallery_name')";

    if ($conn->query($sql) === TRUE) {
        echo "Gallery created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Photo Gallery</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a><br><br>
        <h2>Create Photo Gallery</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Gallery Name: <input type="text" name="gallery_name" required><br><br>
            <input type="submit" value="Create Gallery">
        </form>
    </body>
</html>