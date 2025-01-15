<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<p>Vous devez vous connecter d'abord</p>";
    echo "<a href='../connexion.php'>Lien vers la page de connexion</a>";
    exit;
}

if (!isset($_GET['Date_heure_match'])) {
    echo "<p>Veuillez d'abord sélectionner un match.</p>";
    echo "<a href='feuille_match.php'>Retour au choix des matchs</a>";
    exit;
}

$linkpdo = new PDO("mysql:host=mysql-volleytrack.alwaysdata.net;dbname=volleytrack_bd", "385425", "\$iutinfo");

// Récupération de la liste des joueurs actifs
$requeteJoueurs = $linkpdo->query("
    SELECT idJoueur, CONCAT(Nom, ' ', Prenom) AS NomComplet, 
        Taille, 
        Poids, 
        (SELECT ROUND(SUM(Note)/COUNT(*),1)
                    FROM participer 
                    WHERE participer.idJoueur = j.idJoueur
                    ) AS Moyenne_note, 
        Commentaire 
    FROM joueurs j
    WHERE Statut = 'Actif'
");
$listeJoueurs = $requeteJoueurs->fetchAll(PDO::FETCH_ASSOC);

$dateHeureMatch = $_GET['Date_heure_match'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Ajouter Joueurs au Match</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<header id="myHeader">
    <div id="menunav">
        <ul class="menu-list">
            <img class="headerlogo" src="photo/Headerlogo.png">
            <li><a href="#">Statistiques</a></li>
            <li><a href="Gestion_joueurs_matchs.php">Joueurs</a></li>
            <li><a href="saisie_feuille_match.php">Matchs</a></li>
        </ul>
    </div>
</header>
<body>
    <h1>Saisie des joueurs pour le match</h1>
    <p>Match sélectionné : <?= $dateHeureMatch ?></p>
    <form action="" method="POST" class="tab">
        <input type="hidden" name="Date_heure_match" value="<?= $dateHeureMatch ?>">
        <table>
            <tr>
                <th>Nom</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Moyenne des notes</th>
                <th>Commentaire</th>
                <th>Rôle</th>
                <th>Sélectionner</th>
            </tr>
            <?php foreach ($listeJoueurs as $joueur) { ?>
                <tr>
                    <td><?= $joueur['NomComplet'] ?></td>
                    <td><?= $joueur['Taille'] ?> cm</td>
                    <td><?= $joueur['Poids'] ?> kg</td>
                    <td><?= $joueur['Moyenne_note'] ?></td>
                    <td><?= $joueur['Commentaire'] ?></td>
                    <td>
                        <select name="roles[<?= $joueur['idJoueur'] ?>]">
                            <option value="">-- Sélectionnez un rôle --</option>
                            <option value="Attaquant" 
                                    <?= isset($_POST['roles'][$joueur['idJoueur']]) && $_POST['roles'][$joueur['idJoueur']] == 'Attaquant' ? 'selected' : '' ?>>Attaquant</option>
                            <option value="Passeur" 
                                    <?= isset($_POST['roles'][$joueur['idJoueur']]) && $_POST['roles'][$joueur['idJoueur']] == 'Passeur' ? 'selected' : '' ?>>Passeur</option>
                            <option value="Libero" 
                                    <?= isset($_POST['roles'][$joueur['idJoueur']]) && $_POST['roles'][$joueur['idJoueur']] == 'Libero' ? 'selected' : '' ?>>Libero</option>
                            <option value="Centre" 
                                    <?= isset($_POST['roles'][$joueur['idJoueur']]) && $_POST['roles'][$joueur['idJoueur']] == 'Centre' ? 'selected' : '' ?>>Centre</option>
                            <option value="Remplaçant" 
                                    <?= isset($_POST['roles'][$joueur['idJoueur']]) && $_POST['roles'][$joueur['idJoueur']] == 'Remplaçant' ? 'selected' : '' ?>>Remplaçant</option>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="joueurs[]" value="<?= $joueur['idJoueur'] ?>" 
                                id="joueur<?= $joueur['idJoueur'] ?>"
                                <?= isset($_POST['joueurs']) && in_array($joueur['idJoueur'], $_POST['joueurs']) ? 'checked' : '' ?> />
                    </td>
                </tr>
            <?php } ?>
        </table>

        <button type="submit">Valider la sélection</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $joueurs = array_filter($_POST['joueurs']);
        $roles = $_POST['roles'];

        // Vérifie que le joueur ne soit pas présent 2 fois
        if (count($joueurs) !== count(array_unique($joueurs))) {
            echo '<p>Un joueur ne peut pas être sélectionné plusieurs fois.</p>';
            exit;
        }

        // Préparer l'insertion dans la base de données
        $requete = $linkpdo->prepare("
            INSERT INTO participer (idJoueur, Date_heure_match, Role_titulaire, Poste)
            VALUES (:idJoueur, :Date_heure_match, :Role_titulaire, :Poste)
        ");

        foreach ($joueurs as $idJoueur) {
            $role = $roles[$idJoueur];
            $roleTitulaire = ($role !== 'Remplaçant') ? 1 : 0;

            $requete->execute([
                ':idJoueur' => $idJoueur,
                ':Date_heure_match' => $dateHeureMatch,
                ':Role_titulaire' => $roleTitulaire,
                ':Poste' => $role
            ]);
        }

        echo "<p>Sélection des joueurs enregistrée avec succès pour le match du $dateHeureMatch.</p>";
        echo '<a href="saisie_feuille_match.php">Retour à la sélection des matchs</a>';
    }
    ?>
</body>
</html>
