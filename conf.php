<?php
  session_start();

  include('bd/connexionDB.php');

  if (isset($_SESSION['id'])){
    header('Location: index.php');
    exit;
  }

  $id = (int) $_GET['id'];
  $token = (String) htmlentities($_GET['token']);

  if($id < 1){
    $valid = false;
    $err_mess = "Le lien est erroné";
 
  }elseif(!isset($token)){
    $valid = false;
    $err_mess = "Le lien est erroné";
  }
 
  if($valid){
    $req = $DB->query("SELECT id 
      FROM utilisateur 
      WHERE id = ? AND token = ?",
      array($id, $token));

    $req = $req->fetch();

    if(!isset($req['id'])){
      $valid = false;
      $err_mess = "Le lien n'est plus valide";
    }else{
      $DB->insert("UPDATE utilisateur SET token = NULL, confirmation_token = ? WHERE id = ?",
        array(date('Y-m-d H:i:s'), $req['id']));

      $info_mess = "Votre compte a bien été validé";
    }
  }

  if(isset($err_mess)){
    echo $err_mess;
  }

  if(isset($info_mess)){
    echo $err_mess;
  }
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Validation mail</title>
  </head>
  <body>
    <a href="index.php">Accueil</a>
  </body>
</html>