<?php
$servername = "localhost";
$username = "root"; 
$password = "root"; 
$dbname = "spotify"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteID'])) {
    $deleteID = $_POST['deleteID'];

    $sql = "DELETE FROM spotify_most_streamed_songs WHERE id=$deleteID";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Song deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting song: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="display-2">Songs</h1>
        <div class="row">
            <?php 

                $query  = 'SELECT * FROM spotify_most_streamed_songs';
                $songs = $conn->query($query); 

                if ($songs->num_rows > 0) {
                    foreach ($songs as $song) {
                        echo '
                        <div class="card col-md-4 mb-2">
                            <div class="card-body">
                                <h5 class="card-title">' . $song['new_track_name'] . '</h5>
                                <p class="card-text">Artist: ' . $song['artist_name'] . '</p>
                                <span class="badge bg-secondary">Streams: ' . $song['streams'] . '</span>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <form method="GET" action="updateSong.php">
                                            <input type="hidden" name="songID" value="' . $song['id'] . '">
                                            <button class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <form method="POST" action="">
                                            <input type="hidden" name="deleteID" value="' . $song['id'] . '">
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo "<p>No songs found in the database.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
