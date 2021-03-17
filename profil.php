<?php
session_start();

include('bd/connexionDB.php');

// Si il y a pas de session active alors cette page ne s'affichera pas.
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
    <title>Mon profil</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="title">profil</h1>
            <p class="text-name"><?= $afficher_profil['nom'] . " " .  $afficher_profil['prenom']; ?></p>
                <div class="content-card">
                    <p class="text">Vos informations</p>
                    <ul class="profil-information">
                        <li>Votre nom est :
                            <?= $afficher_profil['nom'] ?>
                        </li>
                        <li>Votre prénom est :
                            <?= $afficher_profil['prenom'] ?></li>
                        <li>Votre mail est : <br>
                            <?= $afficher_profil['mail'] ?>
                        </li>
                        <li>Votre compte a été crée le : <br>
                            <?= $afficher_profil['date_creation_compte'] ?>
                        </li>
                    </ul>
                    <form action="index.php">
                        <button class="button_blue" type="submit">Accueil
                        </button>
                    </form>
                </div>
        </div>
    </div>
</body>
</html>