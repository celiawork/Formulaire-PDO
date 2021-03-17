
<?php
// Permet de savoir s'il y a une session. 
session_start();
// Fichier PHP contenant la connexion de ma BDD
include('bd/connexionDB.php');
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/style.css">
  <title>Accueil</title>
</head>

<body>

  <div class="container"  >
    
    <?php
    if (!isset($_SESSION['id'])) { // Si il y a pas de session alors on verra les liens ci-dessous
    ?>
       
      <div class="card">
        <h1 class="title">Bienvenue</h1>
        <div class="content-card">
          <a href="inscription.php" class="button_blue">Inscription</a>
          <a href="connexion.php" class="button_blue">Connexion</a>
        </div>
      </div>
         
    <?php
    } else { // Sinon s'il y a une session ouverte alors on verra les liens ci-dessous
    ?>

      <div class="container">
        <div class="card">
          <h1 class="title">PROFIL</h1>
          <div class="content-card">
            <a href="profil.php" class="button_blue">Mon profil</a>
            <a href="modifier-profil.php" class="button_blue">Modifier mon profil</a>
            <a href="deconnexion.php" class="button_blue">Deconnexion</a>
          </div>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
</body>
</html>