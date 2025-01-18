<?php
session_start();
if (!isset($_SESSION["login"])) {
    echo "<p>Vous devez vous connecter d'abord</p>";
    echo "<a href='../connexion.php'>Lien vers la page de connexion</a>";
    exit;
}

if (!isset($_GET['id_match'])) {
    echo "<p>Veuillez d'abord sélectionner un match.</p>";
    echo "<a href='feuille_match.php'>Retour au choix des matchs</a>";
    exit;
}

try {
    $linkpdo = new PDO("mysql:host=mysql-volleytrack.alwaysdata.net;dbname=volleytrack_bd", "385425", "\$iutinfo");
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$idMatch = $_GET['id_match'];
$message = '';

$requeteDateMatch = $linkpdo->prepare("
    SELECT Date_heure_match
    FROM matchs
    WHERE id_match = :id;
");
$requeteDateMatch -> execute(array("id" => $idMatch));
$dateHeureMatch = $requeteDateMatch->fetch(); 

// Récupérer les joueurs déjà sélectionnés pour ce match
$requeteJoueursSelectionnes = $linkpdo->prepare("
    SELECT p.idJoueur, CONCAT(j.Nom, ' ', j.Prenom) AS NomComplet, 
        j.Taille, 
        j.Poids, 
        (SELECT ROUND(SUM(Note)/COUNT(*), 1)
         FROM participer 
         WHERE participer.idJoueur = j.idJoueur
        ) AS Moyenne_note, 
        j.Commentaire,
        p.Poste, 
        p.Role_titulaire
    FROM participer p, joueurs j
    WHERE p.idJoueur = j.idJoueur
    AND p.idMatch = :idMatch
");
$requeteJoueursSelectionnes->execute([':idMatch' => $idMatch]);
$joueursSelectionnes = $requeteJoueursSelectionnes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste complète des joueurs actifs
$requeteTousJoueurs = $linkpdo->query("
    SELECT idJoueur, CONCAT(Nom, ' ', Prenom) AS NomComplet, 
        Taille, 
        Poids, 
        (SELECT ROUND(SUM(Note)/COUNT(*), 1)
         FROM participer 
         WHERE participer.idJoueur = j.idJoueur
        ) AS Moyenne_note, 
        Commentaire 
    FROM joueurs j
    WHERE Statut = 'Actif'
");
$listeJoueurs = $requeteTousJoueurs->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $joueurs = isset($_POST['joueurs']) ? array_filter($_POST['joueurs']) : [];
    $roles = isset($_POST['roles']) ? $_POST['roles'] : [];

    // Vérifications des données
    if (count($joueurs) < 6) {
        $message = "Veuillez sélectionner au moins 6 joueurs.";
    } elseif (count($joueurs) > 12) {
        $message = "Vous ne pouvez pas sélectionner plus de 12 joueurs.";
    } elseif (count($joueurs) !== count(array_unique($joueurs))) {
        $message = "Un joueur ne peut pas être sélectionné deux fois.";
    } else {
        foreach ($joueurs as $idJoueur) {
            if (empty($roles[$idJoueur])) {
                $message = "Veuillez attribuer un rôle à chaque joueur sélectionné.";
                break;
            }
        }
    }

    // Si tout est valide, mettre à jour la base de données
    if (empty($message)) {
        try {
            // Supprimer les anciens enregistrements pour ce match
            $linkpdo->prepare("DELETE FROM participer WHERE idMatch = :idMatch")
                ->execute([':idMatch' => $idMatch]);

            // Insérer les nouvelles données
            $requete = $linkpdo->prepare("
                INSERT INTO participer (idJoueur, idMatch, Role_titulaire, Poste)
                VALUES (:idJoueur, :idMatch, :Role_titulaire, :Poste)
            ");

            foreach ($joueurs as $idJoueur) {
                $role = $roles[$idJoueur];
                $roleTitulaire = ($role !== 'Remplaçant') ? 1 : 0;

                $requete->execute([
                    ':idJoueur' => $idJoueur,
                    ':idMatch' => $idMatch,
                    ':Role_titulaire' => $roleTitulaire,
                    ':Poste' => $role
                ]);
            }

            $message = "Modification des joueurs enregistrée avec succès pour le match du ". $dateHeureMatch['Date_heure_match'].".";
        } catch (Exception $e) {
            $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Modifier Joueurs pour le Match</title>
    <link href="../style/style.css" rel="stylesheet">
</head>
<body>
    <header id="myHeader">
        <div id="menunav">
            <ul class="menu-list">
                <img class="headerlogo" src="photo/Headerlogo.png">
                <li><a href="accueil_stat.php">Statistiques</a></li>
                <li><a href="Gestion_joueurs_matchs.php">Joueurs</a></li>
                <li><a href="saisie_feuille_match.php">Matchs</a></li>
            </ul>
        </div>
    </header>
    <h1>Modifier les joueurs pour le match</h1>
    <p>Match sélectionné : <?= $dateHeureMatch['Date_heure_match'] ?></p>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="tab">
        <table>
            <tr>
                <th>Nom</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Moyenne des notes</th>
                <th>Commentaire</th>
                <th>Sélectionner</th>
                <th>Rôle</th>
            </tr>
            <?php foreach ($listeJoueurs as $joueur): 
                $isSelected = false;
                $currentRole = '';
                foreach ($joueursSelectionnes as $joueurSelectionne) {
                    if ($joueurSelectionne['idJoueur'] == $joueur['idJoueur']) {
                        $isSelected = true;
                        $currentRole = $joueurSelectionne['Poste'];
                        break;
                    }
                }
            ?>
                <tr>
                    <td><?= $joueur['NomComplet'] ?></td>
                    <td><?= $joueur['Taille'] ?> cm</td>
                    <td><?= $joueur['Poids'] ?> kg</td>
                    <td><?= $joueur['Moyenne_note']?></td>
                    <td><?= $joueur['Commentaire'] ?></td>
                    <td>
                        <input type="checkbox" name="joueurs[]" value="<?= $joueur['idJoueur'] ?>" <?= $isSelected ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <select name="roles[<?= $joueur['idJoueur'] ?>]">
                            <option value="">-- Sélectionnez un rôle --</option>
                            <option value="Attaquant" <?= $currentRole == 'Attaquant' ? 'selected' : '' ?>>Attaquant</option>
                            <option value="Passeur" <?= $currentRole == 'Passeur' ? 'selected' : '' ?>>Passeur</option>
                            <option value="Libero" <?= $currentRole == 'Libero' ? 'selected' : '' ?>>Libero</option>
                            <option value="Centre" <?= $currentRole == 'Centre' ? 'selected' : '' ?>>Centre</option>
                            <option value="Remplaçant" <?= $currentRole == 'Remplaçant' ? 'selected' : '' ?>>Remplaçant</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button value="" type="submit">Enregistrer les modifications</button>
    </form>
</body>
</html>
