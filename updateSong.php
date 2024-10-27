<?php
$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "spotify"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateID'])) {
    $updateID = $_POST['updateID'];
    $trackName = $_POST['trackName'];
    $artistName = $_POST['artistName'];
    $streams = $_POST['streams'];

    $sql = "UPDATE spotify_most_streamed_songs SET track_name=?, `artist(s)_name`=?, streams=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $trackName, $artistName, $streams, $updateID);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Song updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating song: " . $stmt->error . "</div>";
    }
}


$song = null;
if (isset($_GET['songID'])) {
    $songID = $_GET['songID'];
    $query = "SELECT * FROM spotify_most_streamed_songs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $songID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $song = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Song</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="display-4">Update Song</h1>
        <?php if ($song): ?>
            <form method="POST" action="">
                <input type="hidden" name="updateID" value="<?php echo htmlspecialchars($song['id']); ?>">
                <div class="mb-3">
                    <label for="trackName" class="form-label">Track Name</label>
                    <input type="text" class="form-control" id="trackName" name="trackName" value="<?php echo htmlspecialchars($song['track_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="artistName" class="form-label">Artist Name</label>
                    <input type="text" class="form-control" id="artistName" name="artistName" value="<?php echo htmlspecialchars($song['artist(s)_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="streams" class="form-label">Streams</label>
                    <input type="number" class="form-control" id="streams" name="streams" value="<?php echo htmlspecialchars($song['streams']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Song</button>
            </form>
        <?php else: ?>
            <p class="alert alert-warning">Song not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
