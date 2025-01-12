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

$dateHeureMatch = $_GET['Date_heure_match'];

// Récupération des joueurs déjà sélectionnés pour ce match
$requete = $linkpdo->prepare("
    SELECT p.idJoueur, p.Poste, p.Role_titulaire, CONCAT(j.Nom, ' ', j.Prenom) AS NomComplet
    FROM participer p
    INNER JOIN joueurs j ON p.idJoueur = j.idJoueur
    WHERE p.Date_heure_match = :Date_heure_match
");
$requete->execute([':Date_heure_match' => $dateHeureMatch]);
$joueursSelectionnes = $requete->fetchAll(PDO::FETCH_ASSOC);

// Récupération de tous les joueurs actifs
$requeteJoueurs = $linkpdo->query("
    SELECT idJoueur, CONCAT(Nom, ' ', Prenom) AS NomComplet
    FROM joueurs
    WHERE Statut = 'Actif'
");
$listeJoueurs = $requeteJoueurs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier Joueurs du Match</title>
</head>
<body>
    <h1>Modification des Joueurs pour le Match</h1>
    <p>Match sélectionné : <?= $dateHeureMatch ?></p>
    <form action="" method="POST">
        <input type="hidden" name="Date_heure_match" value="<?= $dateHeureMatch ?>">
        <?php foreach ($joueursSelectionnes as $index => $joueur) { ?>
            <div>
                <label for="joueur<?= $index ?>">Joueur <?= $index + 1 ?> :</label>
                <select name="joueurs[]" id="joueur<?= $index ?>" required>
                    <?php foreach ($listeJoueurs as $option) { ?>
                        <option value="<?= $option['idJoueur'] ?>" <?= $option['idJoueur'] == $joueur['idJoueur'] ? 'selected' : '' ?>>
                            <?= $option['NomComplet'] ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="role<?= $index ?>">Rôle :</label>
                <select name="roles[]" id="role<?= $index ?>" required>
                    <option value="Attaquant" <?= $joueur['Poste'] == 'Attaquant' ? 'selected' : '' ?>>Attaquant</option>
                    <option value="Passeur" <?= $joueur['Poste'] == 'Passeur' ? 'selected' : '' ?>>Passeur</option>
                    <option value="Libero" <?= $joueur['Poste'] == 'Libero' ? 'selected' : '' ?>>Libero</option>
                    <option value="Centre" <?= $joueur['Poste'] == 'Centre' ? 'selected' : '' ?>>Centre</option>
                    <option value="Remplaçant" <?= $joueur['Poste'] == 'Remplaçant' ? 'selected' : '' ?>>Remplaçant</option>
                </select>
            </div>
        <?php } ?>
        <button type="submit">Modifier la sélection</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $joueurs = $_POST['joueurs'];
        $roles = $_POST['roles'];

        $requeteUpdate = $linkpdo->prepare("
            UPDATE participer 
            SET Poste = :Poste, Role_titulaire = :Role_titulaire 
            WHERE idJoueur = :idJoueur AND Date_heure_match = :Date_heure_match
        ");

        foreach ($joueurs as $index => $idJoueur) {
            $role = $roles[$index];
            $roleTitulaire = ($role !== 'Remplaçant') ? 1 : 0;

            $requeteUpdate->execute([
                ':idJoueur' => $idJoueur,
                ':Date_heure_match' => $dateHeureMatch,
                ':Poste' => $role,
                ':Role_titulaire' => $roleTitulaire
            ]);
        }

        echo "<p>La sélection des joueurs a été mise à jour avec succès.</p>";
        echo '<a href="feuille_match.php">Retour à la sélection des matchs</a>';
    }
    ?>
</body>
</html>
