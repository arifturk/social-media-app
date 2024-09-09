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
    $request_id = $_POST["request_id"];
    $action = $_POST["action"];

    $result = $conn->query("SELECT sender_id, receiver_id FROM friend_requests WHERE id='$request_id'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sender_id = $row["sender_id"];
        $receiver_id = $row["receiver_id"];

        if ($action == "accept") {

            if ($conn->query("INSERT INTO friends (user1_id, user2_id) VALUES ('$sender_id', '$receiver_id'), ('$receiver_id', '$sender_id')") === TRUE) {

                if ($conn->query("UPDATE friend_requests SET status='accepted' WHERE id='$request_id'") === TRUE) {
                    echo "Friend request accepted successfully";
                } else {
                    echo "Error updating friend request: " . $conn->error;
                }
            } else {
                echo "Error adding friend: " . $conn->error;
            }
        } elseif ($action == "reject") {

            if ($conn->query("UPDATE friend_requests SET status='rejected' WHERE id='$request_id'") === TRUE) {
                echo "Friend request rejected successfully";
            } else {
                echo "Error updating friend request: " . $conn->error;
            }
        }
    } else {
        echo "Error: Friend request not found";
    }
}

$sql = "SELECT friend_requests.id, users.name, friend_requests.status
        FROM friend_requests 
        JOIN users ON friend_requests.sender_id = users.id 
        WHERE friend_requests.receiver_id = '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Accept Friend Request</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Friend Requests</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>";
                    if ($row["status"] == "pending") {
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                        echo "<input type='hidden' name='request_id' value='" . $row["id"] . "'>";
                        echo "<input type='hidden' name='action' value='accept'>";
                        echo "<input type='submit' value='Accept'>";
                        echo "</form>";
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                        echo "<input type='hidden' name='request_id' value='" . $row["id"] . "'>";
                        echo "<input type='hidden' name='action' value='reject'>";
                        echo "<input type='submit' value='Reject'>";
                        echo "</form>";
                    } else {
                        echo $row["status"];
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No friend requests found</td></tr>";
            }
            ?>
        </table>
    </body>
</html>
