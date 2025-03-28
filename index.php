<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>My Cinema </title>
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
        </div>
        <header>
       <h1>My Cinema</h1>
            <p>Recherchez des films par titre ou par genre</p>
        </header>

        <form action="/" method="GET" class="search-form">
            <div class="form-group">
                <label for="search_movie">Titre du film :</label>
                <input type="text" id="search_movie" name="film_recherche" placeholder="Entrez un titre..." >
            </div>

            <div class="form-group">
                <label for="search_genre">Genre :</label>
                <select name="genre_search" id="search_genre">
                    <option value="">-- Choisir un genre --</option>
                    <option value="Action">Action</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Animation">Animation</option>
                    <option value="Biography">Biography</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Crime">Crime</option>
                    <option value="Drama">Drama</option>
                    <option value="Family">Family</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Horror">Horror</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Romance">Romance</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Thriller">Thriller</option>
                </select>
            </div>
            <label for="search_distributor">Distributeur :</label>
            <input type="text" id="search_distributor" placeholder="Nom du distributeur" name="distributor_search" >

            <button type="submit" class="btn">Rechercher</button>
        </form>
        <a href=""></a>
        <div class="results">
            <?php

            include 'request.php';

            ?>
        </div>

</body>

</html>