

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter un historique à un utilisateurs</title>
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
?>


<form method="POST" action="">
    <label for="user">Choisir un utilisateur :</label>
    <select name="id_user" id="user">
        <?php
       
        $users = $con->query("SELECT id, firstname, lastname FROM user");
        while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($user['id']) . "'>" . htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']) . "</option>";
        }
        ?>
    </select>
    <br>

    <label for="movie">Choisir un film :</label>
    <select name="id_movie" id="movie">
        <?php
        $movies = $con->query("SELECT id, title FROM movie");
        while ($movie = $movies->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($movie['id']) . "'>" . htmlspecialchars($movie['title']) . "</option>";
        }
        ?>
    </select>
    <br>

    <button type="submit">Ajouter au historique</button>
    <br>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $id_user = $_POST['id_user'];
    $id_movie = $_POST['id_movie'];
    $watched_date = date('Y-m-d');  

  
    $sql = "INSERT INTO history (user_id, movie_id, watched_at) 
        VALUES (:user_id, :movie_id, :watched_at)";
    $stmt = $con->prepare($sql);
    
    $stmt->bindParam(':user_id', $id_user, PDO::PARAM_INT);
    $stmt->bindParam(':movie_id', $id_movie, PDO::PARAM_INT);
    $stmt->bindParam(':watched_at', $watched_date, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Film ajouté à l'historique avec succès !";
    } else {
        echo "Erreur lors de l'ajout du film à l'historique.";
    }
}
?>
 <!-- SELECT * FROM history; -->