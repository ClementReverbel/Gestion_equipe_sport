<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="utf-8">
        <title>Gestion volley : Gestion joueurs/matchs</title>
        <link href="style/style_connexion.css" rel="stylesheet">
    </head>
    <body>
        <h2>Ajouter un joueur</h2>
        <form method="POST" action="">
                    <li>
                        <label>Utilisateur : </label>
                        <input type="text" name="nomUtil" required>
                    </li>
                    <li>
                        <label>Mot de pase : </label>
                        <input type="text" name="mdp" required>
                    </li>
                    <input type="submit" name="submdp" value="Entrer"\>
                </form>
        <?php
        try{
            $linkpdo = new PDO("mysql:host=localhost;dbname=gestion_sport","root","");
        }
        catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
        ?>
    </body>
    </body>
</html>