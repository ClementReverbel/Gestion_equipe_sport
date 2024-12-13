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
        <div id ="ajout_joueur">
            <h3>Ajouter un joueur</h2>
            <form method="POST" action="">
                <li>
                    <label>Numéro de licence : </label>
                    <input type="text" name="NumLic" required>
                </li>
                <li>
                    <label>Nom : </label>
                    <input type="text" name="nomJ" required>
                </li>
                <li>
                    <label>Prénom : </label>
                    <input type="text" name="prenomJ" required>
                </li>
                <li>
                    <label>Date de naissance : </label>
                    <input type="date" name="dateJ" required>
                </li>
                <li>
                    <label>Taille (en mètre) : </label>
                    <input type="number" id="taille" name="tailleJ" step="0.01" value="1,75" min="0" srequired>
                </li>
                <li>
                    <label>Poids (en kilo) : </label>
                    <input type="number" id="poid" name="poid" step="1" min="0" required>
                </li>
                <li>
                    <label>Commentaire (facultatif) : </label>
                    <textarea id="commentaire" name="commentaire"></textarea>
                </li>
                <input type="submit" name="submdp" value="+"\>
            </form>
        </div>
        <?php
        try{
            if(isset($_POST['NumLic']) && isset($_POST['nomJ']) && isset($_POST['prenomJ']) && isset($_POST['dateJ']) && isset($_POST['tailleJ']) && isset($_POST['poid']) && isset($_POST['commentaire'])){
            $linkpdo = new PDO("mysql:host=localhost;dbname=volleytrack_bd","root","");
            $requete = $linkpdo->prepare('INSERT INTO joueurs(Numéro_de_licence,Nom,Prenom,Date_de_naissance ,Taille,Poids,Commentaire,Statut) VALUES (:num,:Nom,:Prenom,:Date_de_naissance ,:Taille, :Poids,:Commentaire,:Statut)');
            $requete->execute(array('num'=>$_POST['NumLic'],'Nom'=>$_POST['nomJ'],'Prenom'=>$_POST['prenomJ'],'Date_de_naissance'=>$_POST['dateJ'],'Taille'=>$_POST['tailleJ'],'Poids'=>$_POST['poid'],'Commentaire'=>$_POST['commentaire'],'Statut'=>'Actif'));
            }
        }
        catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
        ?>
    </body>
</html>