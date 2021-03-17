
<?php
session_start();

include('bd/connexionDB.php');

// S'il n'y a pas de session alors on ne va pas sur cette page
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

// On récupère les informations de l'utilisateur connecté
$afficher_profil = $DB->query(
    "SELECT * FROM utilisateur WHERE id = ?",
    array($_SESSION['id'])
);

$afficher_profil = $afficher_profil->fetch();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="css/style.css">
    <title>Accueil</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="content-card">
                <p class="title">Bonjour</p>
                <p class="text-name" id="text-name-home"><?= $afficher_profil['nom'] . " " .  $afficher_profil['prenom']; ?> !</p>
                <p class="text">Merci pour votre inscription, pour aller à votre profil</p>
                <div id="square" class="rollIn animated">
                    <a class="link button_blue" href="index.php">Click ici !</a>
                </div>
            </div>
        </div>
        <script src="/js/jquery-3.4.1.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="js/button.js"></script>
</body>
</html>