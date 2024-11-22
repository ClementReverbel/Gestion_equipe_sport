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
        <?php
            if(!isset($_SESSION["login"])) {
        ?>
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
          
            
        <?php
            if(isset($_POST['nomUtil']) && isset($_POST['mdp'])){
                try{
                    $linkpdo = new PDO("mysql:host=localhost;dbname=gestion_sport","root","");
                }
                catch(Exception $e){
                    die("Erreur: ".$e->getMessage());
                }

                $_SESSION['login'] = $_POST['nomUtil'];

                $requete = $linkpdo->prepare('SELECT mdp FROM utilisateur WHERE login = :login');
                $requete->execute(array('login'=>$_POST['nomUtil']));

                $resultat = $requete->fetch(PDO::FETCH_ASSOC);
                if($resultat){
                    $hash_password = $resultat['mdp'];
                    if(password_verify($_POST['mdp'],$hash_password)){
                        echo('Correct');
                    } else {
                        echo('Mot de passe/Nom utilisateur erroné');
                    }
                }
            } 
        }
        ?>
          </div>

    </body>
</html>