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
    $upload_dir = "uploads/";

    if (!empty($_FILES["photos"]["name"])) {
        foreach ($_FILES["photos"]["name"] as $key => $name) {
            $photo_tmp = $_FILES["photos"]["tmp_name"][$key];
            $photo_name = basename($name);
            $upload_file = $upload_dir . $photo_name;

            if (move_uploaded_file($photo_tmp, $upload_file)) {
                $sql = "INSERT INTO shared_photos (sender_id, receiver_id, path) VALUES ('$user_id', '$friend_id', '$upload_file')";

                if ($conn->query($sql) === TRUE) {
                    echo "Photos shared successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading photo";
            }
        }
    } else {
        echo "No photos selected";
    }
}

$sql = "SELECT users.id, users.name
        FROM users
        JOIN friend_requests ON (friend_requests.sender_id = users.id OR friend_requests.receiver_id = users.id)
        WHERE (friend_requests.sender_id = '$user_id' OR friend_requests.receiver_id = '$user_id')
        AND friend_requests.status = 'accepted' 
        AND users.id != '$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Share Photos</title>
    </head>
    <body>
        <a href="dashboard.php">Dashboard</a>
        <h2>Share Photos</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
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
            <input type="file" name="photos[]" multiple required><br><br>
            <input type="submit" value="Share Photos">
        </form>
    </body>
</html>