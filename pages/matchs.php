<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="utf-8">
        <title>Gestion volley : Gestion joueurs/matchs</title>
        <link href="../style/style.css" rel="stylesheet">
    </head>
    <?php if (!isset($_SESSION["login"])){ ?>
        <p>Vous devez vous connecter d'abord</p>
        <a href="../**.php">Lien vers la page de connexion</a>
    <?php 
        } else { ?>
            <header id="myHeader">  
                <div id="menunav">
                    <ul class="menu-list">
                        <img class="headerlogo" src="photo/Headerlogo.png">
                        <li><a href="accueil_stat.php">Statistiques</a></li>
                        <li><a class="appui" href="#">Joueurs</a></li>
                        <li><a href="sasie_match.php">Matchs</a></li>
                    </ul>
                </div>
            </header>
            <body>
                <!-- formulaire de création d'un match -->
                <div id ="creation_match">
                    <h3>Créer un match</h2>
                    <form method="POST" action="">
                        <li>
                            <label>Nom de l'équipe adverse : </label>
                            <input type="text" name="equipeadv" required>
                        </li>
                        <li>
                            <label>Date du match :</label>
                            <input type="date" name="date" required>
                        </li>
                        <li>
                            <label>heure prévue de la rencontre : </label>
                            <input type="time" name="heure"  required>
                        </li>
                        <li>
                            <label>Match à domicile : </label>
                            <input type="checkbox" name="domicile" >
                        </li>
                        </li>
                        <input type="submit" name="submdp" value="Créer"\>
                    </form>

                </div>
                <?php
                try{
                    $linkpdo = new PDO("mysql:host=mysql-volleytrack.alwaysdata.net;dbname=volleytrack_bd", "385425", "\$iutinfo");

                    //si le bouton à d'envoi à été préssé et que le formulaire est remplit
                    if(isset($_POST['equipeadv']) && 
                    isset($_POST['date']) && isset($_POST['heure']) && 
                    isset($_POST['domicile'])){
                    //Connexion à la bs
                    //Insertion du nouveau match
                    $requete = $linkpdo->prepare('INSERT INTO matchs(Date_heure_match,Nom_equipe_adverse,Rencontre_domicile)
                    VALUES (:date_time,:equipeadv,:domicile)');

                    //Transformation de la date est de l'heure rentrée en type Datetime
                    $heure =  $_POST['heure'];
                    $date = $_POST['date'];
                    $date_time = ($date.' '.$heure.':00');
                   //liaison du formulaire à la requete SQL
                    $requete->execute(array('date_time'=>$date_time,'equipeadv'=>$_POST['equipeadv'],
                    'domicile'=>$_POST['domicile']));
                    }
                    
                    //Création du tableau rempli avec les matchs
                    echo "<table>
                        <tr>
                            <th>Date et heure du match</th>
                            <th>Équipe adverse</th>
                            <th>Rencontre à domicile</th>
                            <th>Score</th>
                            <th>Résultat</th>
                        </tr>";
                    //remplissage du tableau
                    $matchs = $linkpdo->query("SELECT * FROM matchs");
                    while ($match = $matchs->fetch(PDO::FETCH_ASSOC)) {
                        $domicile = "";
                        //Changement du type boolean en oui ou non
                        if($match['Rencontre_domicile'] === 1){
                            $domicile = "OUI";
                        } else {
                            $domicile = "NON";
                        }

                        $gagne = "";
                        //Chagement du type boolean en Gagné ou perdu
                        if($match['Resultat'] === 1){
                            $gagne = "GAGNÉ";
                        } else if($match['Resultat'] === 0){
                            $gagne = "PERDU";
                        }
                        //Création des lignes
                        echo "
                            <tr>
                                <td>{$match['Date_heure_match']}</td>
                                <td>{$match['Nom_equipe_adverse']}</td>
                                <td>{$domicile}</td>
                                <td>{$match['Score']}</td>
                                <td>{$gagne}</td>
                            </tr>";
                    }
                    echo "</table>";
                }
                catch(Exception $e){
                    echo "Erreur: ".$e->getMessage();
                }
        }
                ?>
</html>