<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Détails de l'utilisateur</title>
</head>

<body>
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
</body>
</html>


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


$sql = "SELECT movie.title, movie_schedule.date_begin
        FROM movie_schedule
        JOIN movie ON movie_schedule.id_movie = movie.id
        WHERE movie_schedule.date_begin = CURDATE()";

$stmt = $con->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "<h2>Films projetés ce soir :</h2>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['date_begin']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun film projeté ce soir.</p>";
}
?>


