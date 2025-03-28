<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Cinema</title>
</head>

<body>
    <div class="container">  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
    <nav class="nav">
    <img class ="logo" src="image.png"alt="My Cinema Logo" style="height: 50px; vertical-align: middle">

      <!-- <button><a href="">HOME</a></button> -->
      <button><a href="index.php">MOVIE</a></button>
            <button><a href="search_user.php">USERS</a></button>
            <button><a href="membership.php">MEMBERSHIP</a></button>
            <button><a href="history_user.php">HISTORY_USER</a></button>
            <button><a href="add_movie.php">ADD_MOVIE</a></button>
            <button><a href="movie_project.php">MOVIE_PROJECT</a></button>
            <button><a href="movie_watch.php">MOVIE_WATCH</a></button>

        </nav>


        <header>
            <h1>Recherche d'Utilisateurs</h1>
        </header>

        <form action="/" method="GET" class="search-form">
            <label for="search_user">Prénom :</label>
            <input type="text" id="searchPrenom_user" placeholder="Prénom utilisateur" name="user_recherche" >

            <label for="search_user">Nom :</label>
            <input type="text" id="searchNom_user" placeholder="Nom utilisateur" name="name_recherche" >

            <button type="submit" class="btn">Rechercher</button>
        </form>

        <div class="results">
            <?php
            include 'request.php';
            ?>
        </div>
    </div>
</body>

</html>


<?php

function getUser(&$user_search, &$name_search)
{
    $con = getDatabaseConnexion();
    $sql = "SELECT firstname, lastname,user.id  FROM user   WHERE firstname LIKE :user_search AND lastname LIKE :name_search ";
    $requete = $con->prepare($sql);


    $requete->execute([
        'user_search' => '%' . $user_search . '%',
        'name_search' => '%' . $name_search . '%',
    ]);

    $results = $requete->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo "<table border='1' style='border-collapse: collapse; width: 60%; margin: 20px auto;'>";
        echo "<thead>";
        echo "<tr><th>Prénom</th><th>Nom</th></tr>";
        echo "</thead><tbody>";

        foreach ($results as $row) {
            $id = $row["id"];
            echo "<tr>";
            echo "<td style='background-color:white;'><a href='detail_history.php?id=".$id."'>" . htmlspecialchars($row['firstname']) . "</a></td>";
            echo "<td style='background-color:white;'><a href='detail_history.php?id=".$id."'>" . htmlspecialchars($row['lastname']) . "</a></td>";
            echo "</tr >";
    
        }

        echo "</tbody></table>";
    } else {
        echo "<p style='text-align: center; color: red;'>Aucun utilisateur trouvé.</p>";
    }
}
if (!empty($_GET['user_recherche']) == '' || !empty($_GET['name_recherche']) == '') {
    $user_recherche = isset($_GET['user_recherche']) ? $_GET['user_recherche'] : '';
    $name_recherche = isset($_GET['name_recherche']) ? $_GET['name_recherche'] : '';
    getUser($user_recherche, $name_recherche);
}

?>