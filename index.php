<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Gestion volley : connexion</title>
        <link href="style/style_connexion.css" rel="stylesheet">
        <?php
        session_start();
        ?>
    </head>
    <body>
        <div id="connex">
            <h3>Connectez vous pour acceder à l'application</h3>
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
        </div>
    </body>
</html>