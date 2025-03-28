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
<img class ="logo" src="image.png" alt="My Cinema Logo" style="height: 50px; vertical-align: middle">
<!-- <button><a href="">HOME</a></button> -->
<button><a href="index.php">MOVIE</a></button>
            <button><a href="search_user.php">USERS</a></button>
            <button><a href="membership.php">MEMBERSHIP</a></button>
            <button><a href="history_user.php">HISTORY_USER</a></button>
            <button><a href="add_movie.php">ADD_MOVIE</a></button>
            <button><a href="movie_project.php">MOVIE_PROJECT</a></button>
            <button><a href="movie_watch.php">MOVIE_WATCH</a></button>

        </nav>
    <h1>Détails de l'utilisateur</h1>
    <h1>Rechercher un utilisateur</h1>
<form method="POST">
    <label for="search_user">Nom ou prénom :</label>
    <input type="text" id="search_user" name="search_user" required>
    <button type="submit">Rechercher</button>
    <input type="number" min="1" name="id_membership" placeholder="Id du client">
    <input type="number" min="1" name="id_session" placeholder="Id de movie schedule">
    <input type="submit" name="submit" value="Submit">
</form>
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_user'])) {
    
        $search = $_POST['search_user'];

        $sql = "SELECT * FROM user WHERE firstname LIKE :search OR lastname LIKE :search";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            echo "<h1>Résultats de la recherche</h1>";
            foreach ($users as $user) {
                echo "<p>Utilisateur trouvé : " . htmlspecialchars($user['firstname']) . " " . htmlspecialchars($user['lastname']) . "</p>";
                echo "<a href='?id=" . $user['id'] . "'>Voir l'historique</a>";
            }
        } else {
            echo "<p>Aucun utilisateur trouvé.</p>";
        }
    }

   
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_membership = $_POST["id_membership"];
            $id_session = $_POST["id_session"];
        $insertSql = "INSERT INTO membership_log (id_membership, id_session) VALUES ('$id_membership','$id_session')";
        $insertStmt = $con->prepare($insertSql);
        $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $insertStmt->bindParam(':movie_id', $movieId, PDO::PARAM_INT);
        $insertStmt->bindParam(':date_begin', $dateViewed, PDO::PARAM_STR);
            echo "<p></p>";

        
     }
}




if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "SELECT movie.title, movie.release_date, movie_schedule.date_begin AS date_begin 
            FROM membership_log 
            JOIN membership ON membership_log.id_membership = membership.id 
            JOIN movie_schedule ON membership_log.id_session = movie_schedule.id 
            JOIN movie ON movie_schedule.id_movie = movie.id 
            WHERE membership.id_user = :user_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    echo "<h1>Historique des films</h1>";

    if ($stmt->rowCount() > 0) {
        echo "<table border='1' style='width: 100%; text-align: left;'>";
        echo "<thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de sortie</th>
                    <th>Date vue</th>
                </tr>
              </thead>";
        echo "<tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['release_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_begin']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo '<h2>Ajouter un film à cet utilisateur</h2>';
        echo '<form method="POST">';
        echo '<input type="hidden" name="user_id" value="' . $userId . '">';
        echo '<label for="movie_id">ID du film :</label>';
        echo '<input type="number" id="movie_id" name="movie_id" required>';
        echo '<button type="submit" name="add_movie">Ajouter le film</button>';
        echo '</form>';
    } else {
        echo "<p></p>";
    }
}

?>

