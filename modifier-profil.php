<?php
session_start();

include('bd/connexionDB.php');

if (!isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

// On récupère les informations de l'utilisateur connecté
$afficher_profil = $DB->query(
    "SELECT * 
FROM utilisateur 
WHERE id = ?",
    array($_SESSION['id'])
);

$afficher_profil = $afficher_profil->fetch();

if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    if (isset($_POST['modification'])) {

        $nom = htmlentities(trim($nom));
        $prenom = htmlentities(trim($prenom));
        $mail = htmlentities(strtolower(trim($mail)));

        if (empty($nom)) {
            $valid = false;
            $er_nom = "Il faut mettre un nom";
        }

        if (empty($prenom)) {
            $valid = false;
            $er_prenom = "Il faut mettre un prénom";
        }

        if (empty($mail)) {
            $valid = false;
            $er_mail = "Il faut mettre un mail";
        } elseif (!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)) {
            $valid = false;
            $er_mail = "Le mail n'est pas valide";
        } else {
            // On vérifit que le mail est disponible
            $req_mail = $DB->query(
                "SELECT mail FROM utilisateur WHERE mail = ?",
                array($mail)
            );

            $resultat = $req_mail->fetch();

            if ($resultat) {
                $valid = false;
                $er_mail = "Ce mail existe déjà";
            }
        }

        if ($valid) {

            $DB->insert(
                "UPDATE utilisateur SET prenom = ?, nom = ?, mail = ? 
WHERE id = ?",
                array($prenom, $nom, $mail, $_SESSION['id'])
            );

            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['mail'] = $mail;

            header('Location:  profil.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
       
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="css/style.css">
    <title>Modifier votre profil</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="title">MODIFICATION</h1>
            <form class="content-card" method="post">
                <?php
                if (isset($er_nom)) {
                ?>
                    <div><?= $er_nom ?></div>
                <?php
                }
                ?>
                <input class="input" type="text" placeholder="Votre nom" name="nom" value="<?php if (isset($nom)) {
                                                                                                echo $nom;
                                                                                            } else {
                                                                                                echo $afficher_profil['nom'];
                                                                                            } ?>" required>
                <?php
                if (isset($er_prenom)) {
                ?>
                    <div><?= $er_prenom ?></div>
                <?php
                }
                ?>
                <input class="input" type="text" placeholder="Votre prénom" name="prenom" value="<?php if (isset($prenom)) {
                                                                                                        echo $prenom;
                                                                                                    } else {
                                                                                                        echo $afficher_profil['prenom'];
                                                                                                    } ?>" required>
                <?php
                if (isset($er_mail)) {
                ?>
                    <div><?= $er_mail ?></div>
                <?php
                }
                ?>
                <input class="input" type="email" placeholder="Adresse mail" name="mail" value="<?php if (isset($mail)) {
                                                                                                    echo $mail;
                                                                                                } else {
                                                                                                    echo $afficher_profil['mail'];
                                                                                                } ?>" required>


                <button class="button_blue" type="submit" name="modification">Modifier</button>
                <button class="button_blue">
                  <a class="link" href="index.php">Accueil</a>
                </button>
               
            </form>
        </div>
    </div>
</body>
</html>