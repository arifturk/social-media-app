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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>List Users</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>List of Users</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }
            ?>
        </table>
    </body>
</html>