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
    $name = $_POST["name"];
    $bio = $_POST["bio"];

    $sql = "UPDATE users SET name='$name', bio='$bio' WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["name"];
    $bio = $row["bio"];
} else {
    echo "Error: User not found";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Profile</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Edit Profile</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Name:</label> <br>
            <input type="text" name="name" value="<?php echo $name; ?>" required><br><br>
            <label for="bio">Bio:</label> <br>
            <textarea name="bio"><?php echo $bio; ?></textarea><br><br>
            <input type="submit" value="Update">
        </form>
    </body>
</html>