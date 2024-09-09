<?php
session_start();

$conn = new mysqli("localhost", "Arif", "abc123", "app");

// Check connection
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
    $message = $_POST["message"];

    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '$friend_id', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT users.id, users.name
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
        <title>Send Message</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a><br><br>
        <h2>Send Message</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <select name="friend_id">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No friends found</option>";
                }
                ?>
            </select>
            <textarea name="message" required></textarea>
            <input type="submit" value="Send Message">
        </form>
    </body>
</html>