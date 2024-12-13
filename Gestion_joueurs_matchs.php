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
            if(isset($_POST['submdp']) && isset($_POST['NumLic']) && isset($_POST['nomJ']) && isset($_POST['prenomJ']) && isset($_POST['dateJ']) && isset($_POST['tailleJ']) && isset($_POST['poid']) && isset($_POST['commentaire'])){
            $linkpdo = new PDO("mysql:host=localhost;dbname=volleytrack_bd","root","");
            $requete = $linkpdo->prepare('INSERT INTO joueurs(Numéro_de_licence,Nom,Prenom,Date_de_naissance ,Taille,Poids,Commentaire,Statut) VALUES (:num,:Nom,:Prenom,:Date_de_naissance ,:Taille, :Poids,:Commentaire,:Statut)');
            $requete->execute(array('num'=>$_POST['NumLic'],'Nom'=>$_POST['nomJ'],'Prenom'=>$_POST['prenomJ'],'Date_de_naissance'=>$_POST['dateJ'],'Taille'=>$_POST['tailleJ'],'Poids'=>$_POST['poid'],'Commentaire'=>$_POST['commentaire'],'Statut'=>'Actif'));
            }
        }
        catch(Exception $e){
            die("Erreur: ".$e->getMessage());
        }
        ?>

        <div id="recherche_joueur">
        <h3>Rechercher un joueur</h3>
        <form method="POST" action="">
            <label>Numéro de licence : </label>
            <input type="text" name="rechNumLic" required>
            <input type="submit" name="rech" value="Rechercher">
        </form>
    </div>

    <?php
    try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=volleytrack_bd", "root", "");

        if (isset($_POST['rech']) && !empty($_POST['rechNumLic'])) {
            $rechNumLic = $_POST['rechNumLic'];
            $requete = $linkpdo->prepare('SELECT * FROM joueurs WHERE Numéro_de_licence = :num');
            $requete->execute(['num' => $rechNumLic]);
            $joueur = $requete->fetch(PDO::FETCH_ASSOC);

            if ($joueur) {
                ?>
                <div id="modifier_joueur">
                    <h3>Modifier un joueur</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="NumLic" value="<?php echo $joueur['Numéro_de_licence'] ?>">
                        <li>
                            <label>Nom : </label>
                            <input type="text" name="nomJ" value="<?php echo $joueur['Nom'] ?>" required>
                        </li>
                        <li>
                            <label>Prénom : </label>
                            <input type="text" name="prenomJ" value="<?php echo $joueur['Prenom'] ?>" required>
                        </li>
                        <li>
                            <label>Date de naissance : </label>
                            <input type="date" name="dateJ" value="<?php echo $joueur['Date_de_naissance'] ?>" required>
                        </li>
                        <li>
                            <label>Taille (en mètre) : </label>
                            <input type="number" name="tailleJ" step="0.01" min="0" value="<?php echo $joueur['Taille'] ?>" required>
                        </li>
                        <li>
                            <label>Poids (en kilo) : </label>
                            <input type="number" name="poid" step="1" min="0" value="<?php echo $joueur['Poids']; ?>" required>
                        </li>
                        <li>
                            <label>Commentaire : </label>
                            <textarea name="commentaire"><?php echo $joueur['Commentaire'] ?></textarea>
                        </li>
                        <input type="submit" name="update" value="Mettre à jour">
                    </form>
                </div>
                <?php
            } else {
                echo "<p>Joueur introuvable avec ce numéro de licence.</p>";
            }
        }

        if (isset($_POST['update'])) {
            $updateRequete = $linkpdo->prepare('UPDATE joueurs SET Nom = :Nom, Prenom = :Prenom, Date_de_naissance = :Date_de_naissance, Taille = :Taille, Poids = :Poids, Commentaire = :Commentaire WHERE Numéro_de_licence = :num');
            $updateRequete->execute([
                'Nom' => $_POST['nomJ'],
                'Prenom' => $_POST['prenomJ'],
                'Date_de_naissance' => $_POST['dateJ'],
                'Taille' => $_POST['tailleJ'],
                'Poids' => $_POST['poid'],
                'Commentaire' => $_POST['commentaire'],
                'num' => $_POST['NumLic'],
            ]);

            echo "<p>Joueur mis à jour avec succès !</p>";
        }
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
    ?>
    </body>
</html>