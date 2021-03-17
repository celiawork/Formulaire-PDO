<?php
session_start();

include('bd/connexionDB.php');

if (isset($_SESSION['id'])) {
    header('Location: accueil.php');
    exit;
}
if (!empty($_POST)) {
    extract($_POST);
    $valid = true;


    if (isset($_POST['connexion'])) {

        $mail = htmlentities(strtolower(trim($mail)));
        $mdp_hash = password_hash(($mdp), PASSWORD_DEFAULT);

        //Vérification si il y a un e-mail de renseigné

        if (empty($mail)) {
            $valid = false;
            $er_mail = "Il faut mettre un e-mail";
        }
        //Vérification si il y a un mot de passe de renseigné
        if (empty($mdp)) {
            $valid = false;
            $er_mdp = "Il faut mettre un mot de passe";
        }

        //Récupération de l'utilisateur et de son mot de passe
        $req = $DB->query('SELECT id, mdp FROM utilisateur WHERE mail = :mail');
        $req->execute(array('mail' => $mail));
        $resultat = $req->fetch();

        $isPasswordCorrect = password_verify($_POST['mdp'], $resultat['mdp']);

        if (!$resultat) {
            echo 'Mauvais adresse e-mail ou mot de passe !';
        } else {
            if ($isPasswordCorrect) {

                $_SESSION['id'] = $resultat['id'];
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['mail'] = $mail;

                header('Location:index.php');
            } else {
                echo 'Mauvais adresse e-mail ou mot de passe !';
            }
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
    <title>Connexion</title>
</head>

<body>
    <div class="container">
        <div class="card">
         <h1 class="title">CONNEXION</h1>
            <form class="content-card" method="post">
                

                <?php
                if (isset($er_mail)) {
                ?>
                    <div><?= $er_mail ?></div>
                <?php
                }
                ?>

                <input class="input" type="email" placeholder="Adresse mail" name="mail" value="<?php if (isset($mail)) {
                                                                                                    echo $mail;
                                                                                                } ?>" required>

                <?php
                if (isset($er_mdp)) {
                ?>
                    <div><?= $er_mdp ?></div>
                <?php
                }
                ?>

                <input class="input" type="password" placeholder="Mot de passe" name="mdp" value="<?php if (isset($mdp)) {
                                                                                                        echo $mdp;
                                                                                                    } ?>" required>
                <button class="button_blue" type="submit" name="connexion">Se connecter</button>
                 <a class="link-lost-password" href="motdepasse.php">Mot de passe oublié</a>

            </form>
        </div>
    </div>
</body>
</html>