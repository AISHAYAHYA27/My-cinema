<?php

$host = 'localhost';
$dbname = 'cinema';
$user = 'aisha';
$password = 'aisha@27';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_movie = $_POST['id_movie'];
    $id_room = $_POST['id_room'];
    $date_begin = $_POST['date_begin'];
    $date_end = $_POST['date_end'];


    $roomSql = "SELECT id FROM room WHERE id = :id_room";
    $roomStmt = $con->prepare($roomSql);
    $roomStmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);
    $roomStmt->execute();

    if ($roomStmt->rowCount() > 0) {

        $sql = "INSERT INTO movie_schedule (id_movie, id_room, date_begin, end_time)
                VALUES (:id_movie, :id_room, :date_begin, :end_time)";
        $stmt = $con->prepare($sql);


        $stmt->bindParam(':id_movie', $id_movie, PDO::PARAM_INT);
        $stmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);
        $stmt->bindParam(':date_begin', $date_begin, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $date_end, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<p>Séance ajoutée avec succès !</p>";
        } else {
            echo "<p>Une erreur s'est produite.</p>";
        }
    } else {
        echo "<p>La salle n'existe pas.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter une séance</title>
</head>

<body>
    <div class="container">
        <nav class="nav">
            <img class="logo" src="image.png" alt="My Cinema Logo" style="height: 50px; vertical-align: middle">
            <!-- <button><a href="">HOME</a></button> -->
            <button><a href="index.php">MOVIE</a></button>
            <button><a href="search_user.php">USERS</a></button>
            <button><a href="membership.php">MEMBERSHIP</a></button>
            <button><a href="history_user.php">HISTORY_USER</a></button>
            <button><a href="add_movie.php">ADD_MOVIE</a></button>
            <button><a href="movie_project.php">MOVIE_PROJECT</a></button>
            <button><a href="movie_watch.php">MOVIE_WATCH</a></button>
        </nav>
    </div>
    <h1>Ajouter une séance pour un film</h1>

    <form method="POST" action="">
        <label for="movie">Choisir un film :</label>
        <select name="id_movie" id="movie" required>
            <?php

            $movies = $con->query("SELECT id, title FROM movie");
            while ($movie = $movies->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($movie['id']) . "'>" . htmlspecialchars($movie['title']) . "</option>";
            }
            ?>
        </select><br>

        <label for="room">Choisir une salle :</label>
        <select name="id_room" id="room" required>
            <?php

            $rooms = $con->query("SELECT id, name FROM room");
            while ($room = $rooms->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($room['id']) . "'>" . htmlspecialchars($room['name']) . "</option>";
            }
            ?>
        </select><br>

        <label for="date_begin">Date et heure de début :</label>
        <input type="datetime-local" name="date_begin" required><br>

        <label for="date_end">Date et heure de fin :</label>
        <input type="datetime-local" name="date_end" required><br>

        <button type="submit">Ajouter la séance</button>
    </form>

</body>

</html>

<!-- SELECT * FROM movie_schedule WHERE date_begin >= CURDATE(); -->