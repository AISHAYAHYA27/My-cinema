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

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

  
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subscription'])) {
        $newSubscriptionId = $_POST['subscription_id'] ?? null;
        
     
        if ($newSubscriptionId == 0) {
            $newSubscriptionId = null; 
        }
    
        if ($newSubscriptionId !== null) {
            $updateSql = "UPDATE user SET subscription_id = :subscription_id WHERE id = :id";
            $updateStmt = $con->prepare($updateSql);
            $updateStmt->bindParam(':subscription_id', $newSubscriptionId, PDO::PARAM_INT);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($updateStmt->execute()) {
                echo "<p>Abonnement mis à jour avec succès !</p>";
            } else {
                echo "<p> Une erreur s'est produite .</p>";
            }
        } else {
            echo "<p>Aucun abonnement sélectionné (l'utilisateur n'a pas d'abonnement).</p>";
        }
    }

    $sql = "SELECT user.firstname, user.lastname, user.birthdate, user.email, user.address, 
                   subscription.name AS subscription_name 
            FROM user 
            LEFT JOIN subscription ON user.subscription_id = subscription.id 
            WHERE user.id = :id";
    $stmt = $con->prepare($sql);
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
    } else {
        echo "<p>Aucun utilisateur trouvé avec cet ID.</p>";
        exit;
    }
} else {
    echo "<p>Aucun ID utilisateur fourni.</p>";
    exit;
}
?>

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

    <h1>Détails de l'utilisateur</h1>
    <p><strong>Prénom :</strong> <?php echo htmlspecialchars($user['firstname']); ?></p>
    <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['lastname']); ?></p>
    <p><strong>Date de naissance :</strong> <?php echo htmlspecialchars($user['birthdate']); ?></p>
    <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    <p><strong>Abonnement :</strong> <?php echo htmlspecialchars($user['subscription_name'] ?? 'Aucun'); ?></p>

    <h2>Modifier l'abonnement</h2>
    <form method="POST" action="?id=<?php echo $id; ?>">
    <label for="subscription">Choisir un abonnement :</label>
    <select name="subscription_id" id="subscription">
        <option value="">-- Sélectionner un abonnement --</option>
      
        <?php
     
        $subscriptionQuery = $con->query("SELECT id, name FROM subscription");
        while ($subscription = $subscriptionQuery->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($subscription['id']) . "'>" .
                htmlspecialchars($subscription['name']) .
                "</option>";
        }

        ?>
    </select>
    <button type="submit" name="update_subscription">Mettre à jour</button>
</form>
       
</body>

</html>