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
        <!-- <a href="">HOME</a> -->
        <button><a href="index.php">MOVIE</a></button>
           <a href="search_user.php">USERS</a>
           <a href="membership.php">MEMBERSHIP</a>
         <a href="history_user.php">HISTORY_USER</a>
          <a href="add_movie.php">ADD_MOVIE</a>
         <a href="movie_project.php">MOVIE_PROJECT</a>
          <a href="movie_watch.php">MOVIE_WATCH</a>
        <header>
            <h1>Search Member</h1>
        </header>
        <form action="#" method="GET" class="search-form">
            <label >Firstname:</label>
            <input type="text" id="searchPrenom_user" placeholder="Firstname user.." name="user_search" >

            <label >Lastname:</label>
            <input type="text" id="searchNom_user" placeholder="Lastname user.." name="name_recherche" >

            <button type="submit" class="btn">Search</button>
        </form>

        </div>
        <div class="results">
            <?php
          
            $host = 'localhost';
            $dbname = 'cinema';
            $user = 'aisha';
            $password = 'aisha@27';

            try {
                $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }

         
            if (isset($_GET['user_search']) || isset($_GET['name_recherche'])) {
                $user_search = $_GET['user_search'] ?? '';
                $name_search = $_GET['name_recherche'] ?? '';

              
                $sql = "SELECT id, firstname, lastname 
                        FROM user 
                        WHERE firstname LIKE :firstname OR lastname LIKE :lastname";
                $stmt = $con->prepare($sql);
                $stmt->execute([
                    ':firstname' => '%' . $user_search . '%',
                    ':lastname' => '%' . $name_search . '%',
                ]);

                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($users)) {
                    echo "<h2>Search Results:</h2>";
                    echo "<ul>";
                    foreach ($users as $user) {
                     
                        echo "<li>
                                <a href='?id=" . $user['id'] . "'>" . 
                                htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) . 
                              "</a>
                              </li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No users found.</p>";
                }
            }

           
            if (isset($_GET['id'])) {
                $userId = $_GET['id'];

                $sql = "SELECT membership_log.id_membership, membership_log.id_session, membership.id_user, 
                        user.firstname, user.lastname, 
                        movie.title, subscription.name AS subscription_name, movie_schedule.date_begin, movie.release_date
                        FROM membership_log 
                        JOIN membership ON membership_log.id_membership = membership.id 
                        JOIN movie_schedule ON membership_log.id_session = movie_schedule.id 
                        JOIN user ON membership.id_user = user.id 
                        JOIN movie ON movie_schedule.id_movie = movie.id 
                        JOIN subscription ON membership.id_subscription = subscription.id
                        WHERE user.id = :user_id";
                $stmt = $con->prepare($sql);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    echo "<h2>Historique des films</h2>";
                    echo "<table border='1' style='width: 100%; text-align: left;'>";
                    echo "<thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Title</th>
                                <th>Session</th>
                                <th>Release Date</th>
                                <th>Date Begin</th>
                                <th>Subscription</th>
                            </tr>
                          </thead>";
                    echo "<tbody>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['firstname'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['lastname'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['title'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['id_session'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['release_date'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_begin'] ?? 'Non défini') . "</td>";
                        echo "<td>" . htmlspecialchars($row['subscription_name'] ?? 'Non défini') . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No movie history found for this user.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>