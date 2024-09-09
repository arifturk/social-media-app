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
    $friend_id = $_POST["friend_id"];

    $sql = "INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES ('$user_id', '$friend_id', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "Friend request sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM users WHERE id != '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Send Friend Request</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a><br><br>
        <h2>Send Friend Request</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <select name="friend_id">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No users found</option>";
                }
                ?>
            </select>
            <input type="submit" value="Send Request">
        </form>
    </body>
</html>