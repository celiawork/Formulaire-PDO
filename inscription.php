<?php
session_start();

include('bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

// Si il y a une session active alors cette page ne s'affichera pas.
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}

// Si la variable "$_Post" contient des informations alors on les traitres
if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    // On se place sur le bon formulaire grâce au "name" de la balise "input"
    if (isset($_POST['inscription'])) {

     
        $nom  = htmlspecialchars(trim($nom)); // On récupère le nom
        $prenom = htmlspecialchars(trim($prenom)); // on récupère le prénom
        $mail = htmlspecialchars(strtolower(trim($mail))); // On récupère le mail
        $mdp_hash = password_hash(($mdp), PASSWORD_DEFAULT); // On récupère le mot de passe 
        $mdp_hash = password_hash(($confmdp), PASSWORD_DEFAULT); //  On récupère la confirmation du mot de passe

        //  Vérification du nom
        if (empty($nom)) {
            $valid = false;
            $er_nom = "Le nom d'utilisateur ne peut pas être vide";
        }

        //  Vérification du prénom
        if (empty($prenom)) {
            $valid = false;
            $er_prenom = "Le prénom d'utilisateur ne peut pas être vide";
        }

        // Vérification du mail
        if (empty($mail)) {
            $valid = false;
            $er_mail = "Le mail ne peut pas être vide";

            // On vérifie que le mail est dans le bon format
        } elseif (!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)) {
            $valid = false;
            $er_mail = "Le mail n'est pas valide";
        } else {
            // On vérifie que le mail est disponible
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


        // Vérification du mot de passe
        if (empty($mdp)) {
            $valid = false;
            $er_mdp = "Le mot de passe ne peut pas être vide";
        } elseif ($mdp != $confmdp) {
            $valid = false;
            $er_mdp = "La confirmation du mot de passe ne correspond pas";
        }

        // Si toutes les conditions sont remplies alors on fait le traitement
        if ($valid) {

            $date_creation_compte = date('Y-m-d H:i:s');
            $mdp_hash = password_hash(($mdp), PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(12));

            // On insert nos données dans la table utilisateur
            $DB->insert(
                "INSERT INTO utilisateur (nom, prenom, mail, mdp, date_creation_compte, token) VALUES  (?, ?, ?, ?, ?, ?)",
                array($nom, $prenom, $mail, $mdp_hash, $date_creation_compte, $token)
            );

            $req = $DB->query(
                "SELECT * FROM utilisateur WHERE mail = ?",
                array($mail)
            );

            $req = $req->fetch();

            $mail_to = $req['mail'];

            //=====Création du header de l'e-mail.
            $header = "From: no-reply <celiawork@outlook.fr>\n";
            $header .= "MIME-version: 1.0\n";
            $header .= "Content-type: text/html; charset=utf-8\n";
            $header .= "Content-Transfer-ncoding: 8bit";
            //=======

            //=====Ajout du message au format HTML          
            $contenu = '<p>Bonjour ' . $req['nom'] . ',</p><br>
            <p>Veuillez confirmer votre compte <a href="http://www.domaine.com/conf.php?id=' . $req['id'] . '&token=' . $token . '">Valider</a><p>';

            mail($mail_to, 'Confirmation de votre compte', $contenu, $header);

            //CONNECTION A LA SESSION

            header('Location: Accueil.php');
            session_start();

            $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
            $_SESSION['nom'] = $req['nom'];
            $_SESSION['prenom'] = $req['prenom'];
            $_SESSION['mail'] = $req['mail'];

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
    <title>Inscription</title>
</head>

<body>
    <div class="contrainer"  >
        <div class="card">
            <h1 class="title">inscription</h1>
            <form class="content-card" method="post">

                <input class="input" type="text" placeholder=" Nom" name="nom" value="<?php if (isset($nom)) {
                                                                                            echo $nom;
                                                                                        } ?>" required>

                <?php
                // S'il y a une erreur sur le nom alors on affiche
                if (isset($er_nom)) {

                ?>
                    <div class="message-error"><?= $er_nom ?></div>
                <?php
                }
                ?>

                <input class="input" type="text" placeholder=" Prénom" name="prenom" value="<?php if (isset($prenom)) {
                                                                                                echo $prenom;
                                                                                            } ?>" required>

                <?php
                if (isset($er_prenom)) {

                ?>
                    <div class="message-error"><?= $er_prenom ?></div>
                <?php
                }
                ?>

                <input class="input" type="email" placeholder="Adresse mail" name="mail" value="<?php if (isset($mail)) {
                                                                                                    echo $mail;
                                                                                                } ?>" required>

                <?php
                if (isset($er_mail)) {

                ?>
                    <div class="message-error"><?= $er_mail ?></div>
                <?php
                }
                ?>

                <input class="input" type="password" placeholder="Mot de passe" name="mdp" value="<?php if (isset($mdp)) {
                                                                                                        echo $mdp;
                                                                                                    } ?>" required>

                <?php
                if (isset($er_mdp)) {

                ?>
                    <div class="message-error"><?= $er_mdp ?></div>
                <?php
                }
                ?>

                <input class="input" type="password" placeholder="Confirmer le mot de passe" name="confmdp" required>
                <button class="button_blue" type="submit" name="inscription">Envoyer</button>
            </form>
        </div>
    </div>
</body>
</html>