<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<p>Vous devez vous connecter d'abord</p>";
    echo "<a href='../connexion.php'>Lien vers la page de connexion</a>";
    exit;
}

$linkpdo = new PDO("mysql:host=mysql-volleytrack.alwaysdata.net;dbname=volleytrack_bd", "385425", "\$iutinfo");

// Récupérer les matchs non sélectionnés
$requeteMatchsNonSelect = $linkpdo->query("
    SELECT Date_heure_match, Nom_equipe_adverse 
    FROM matchs 
    WHERE Date_heure_match NOT IN (
        SELECT DISTINCT Date_heure_match 
        FROM participer
    )
");
$matchsNonSelect = $requeteMatchsNonSelect->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les matchs déjà configurés
$requeteMatchsSelect = $linkpdo->query("
    SELECT DISTINCT m.Date_heure_match, m.Nom_equipe_adverse 
    FROM matchs m, participer p
    WHERE  m.Date_heure_match = p.Date_heure_match
    AND m.Resultat IS NULL
");
$matchsSelect = $requeteMatchsSelect->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Feuille de Match</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<header id="myHeader">
    <div id="menunav">
        <ul class="menu-list">
            <img class="headerlogo" src="photo/Headerlogo.png">
            <li><a href="#">Statistiques</a></li>
            <li><a href="Gestion_joueurs_matchs.php">Joueurs</a></li>
            <li><a href="#" class="appui"> Matchs</a></li>
        </ul>
    </div>
</header>
<body>
    <h1>Gestion des Feuilles de Match</h1>

    <!-- Section pour les nouveaux matchs -->
    <h2>Nouveau Match</h2>
    <form action="ajouter_joueur_match.php" method="GET">
        <label for="match">Choisir un match :</label>
        <select name="Date_heure_match" id="match" required>
            <option value="">-- Sélectionnez un match --</option>
            <?php foreach ($matchsNonSelect as $match) { ?>
                <option value="<?= $match['Date_heure_match'] ?>">
                    <?= $match['Date_heure_match'] ?> - <?= $match['Nom_equipe_adverse'] ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Configurer</button>
    </form>

    <!-- Section pour les matchs déjà configurés -->
    <h2>Modifier un Match Existant</h2>
    <form action="modifier_joueur_match.php" method="GET">
        <label for="matchConfig">Choisir un match configuré :</label>
        <select name="Date_heure_match" id="matchConfig" required>
            <option value="">-- Sélectionnez un match --</option>
            <?php foreach ($matchsSelect as $match) { ?>
                <option value="<?= $match['Date_heure_match'] ?>">
                    <?= $match['Date_heure_match'] ?> - <?= $match['Nom_equipe_adverse'] ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
