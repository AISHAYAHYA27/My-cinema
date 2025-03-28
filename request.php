<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getDatabaseConnexion()
{
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=cinema', 'aisha', 'aisha@27');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

if (!empty($_GET['film_recherche']) || !empty($_GET['genre_search']) || !empty($_GET['distributor_search'])) {
    $film_recherche = isset($_GET['film_recherche']) ? $_GET['film_recherche'] : '';
    $genre_search = isset($_GET['genre_search']) ? $_GET['genre_search'] : '';
    $distributor_search = isset($_GET['distributor_search']) ? $_GET['distributor_search'] : '';

    $conn = getDatabaseConnexion();

    $sql = " SELECT DISTINCT movie.title, genre.name, distributor.name AS distributor  FROM movie INNER JOIN movie_genre ON movie.id = movie_genre.id_movie
        INNER JOIN genre ON movie_genre.id_genre = genre.id LEFT JOIN distributor ON movie.id_distributor = distributor.id WHERE 1 = 1"; 

    $params = [];

    if (!empty($film_recherche)) {
        $sql .= " AND movie.title LIKE :title";
        $params['title'] = '%' . $film_recherche . '%';
    }

    if (!empty($genre_search)) {
        $sql .= " AND genre.name LIKE :genre";
        $params['genre'] = '%' . $genre_search . '%';
    }

    if (!empty($distributor_search)) {
        $sql .= " AND distributor.name LIKE :distributor";
        $params['distributor'] = '%' . $distributor_search . '%';
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
      
        echo "<table border='1' style='border-collapse: collapse; width: 80%; margin: 20px auto;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Numéro</th>";
        echo "<th>Film</th>";
        echo "<th>Genre</th>";
        echo "<th>Distributeur</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $counter = 1;

        foreach ($results as $row) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['distributor']) . "</td>";
            echo "</tr>";
            $counter++;
        }

        echo "</tbody>";
        echo "</table>";

        echo "<p style='text-align: center; margin-top: 20px;'>Nombre total de films trouvés : <strong>" . count($results) . "</strong></p>";
    } else {
        echo "<p style='text-align: center; color: red;'>Aucun résultat trouvé.</p>";
    }
} else {
    echo "<p style='text-align: center;'>Veuillez effectuer une recherche.</p>";
}
?>