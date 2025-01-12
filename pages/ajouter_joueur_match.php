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
    <script>
        // Fonction pour afficher les spécifications d'un joueur
        function afficherDetailsJoueur(joueurId, detailDivId) {
            const joueurs = <?= json_encode($listeJoueurs) ?>;

            // Trouver le joueur sélectionné
            const joueur = joueurs.find(j => j.idJoueur == joueurId);

            // Sélectionner le div d'affichage des détails
            const detailsDiv = document.getElementById(detailDivId);

            // Afficher les détails ou vider le div si aucun joueur n'est sélectionné
            if (joueur) {
                detailsDiv.innerHTML = `
                    <p><strong>Taille :</strong> ${joueur.Taille} cm</p>
                    <p><strong>Poids :</strong> ${joueur.Poids} kg</p>
                    <p><strong>Note Moyenne :</strong> ${joueur.Moyenne_note} /10</p>
                    <p><strong>Commentaire :</strong> ${joueur.Commentaire}</p>
                `;
            } else {
                detailsDiv.innerHTML = "";
            }
        }
    </script>
</head>
<body>
    <h1>Saisie des joueurs pour le match</h1>
    <p>Match sélectionné : <?= $dateHeureMatch ?></p>
    <form action="" method="POST">
        <input type="hidden" name="Date_heure_match" value="<?= $dateHeureMatch ?>">
        <?php for ($i = 1; $i <= 12; $i++) { 
            $isRequired = $i <= 6 ? "required" : ""; ?>
            <div>
                <label for="joueur<?= $i ?>">Joueur <?= $i ?> :</label>
                <select name="joueurs[]" id="joueur<?= $i ?>" <?= $isRequired ?> onchange="afficherDetailsJoueur(this.value, 'details<?= $i ?>')">
                    <option value="">-- Sélectionnez un joueur --</option>
                    <?php foreach ($listeJoueurs as $joueur) { ?>
                        <option value="<?= $joueur['idJoueur'] ?>"><?= $joueur['NomComplet'] ?></option>
                    <?php } ?>
                </select>

                <label for="role<?= $i ?>">Rôle :</label>
                <select name="roles[]" id="role<?= $i ?>" <?= $isRequired ?>>
                    <option value="">-- Sélectionnez un rôle --</option>
                    <option value="Attaquant">Attaquant</option>
                    <option value="Passeur">Passeur</option>
                    <option value="Libero">Libero</option>
                    <option value="Centre">Centre</option>
                    <option value="Remplaçant">Remplaçant</option>
                </select>
            </div>
        <?php } ?>
        <button type="submit">Valider la sélection</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $joueurs = array_filter($_POST['joueurs']);
        $roles = $_POST['roles'];

        if (count($joueurs) !== count(array_unique($joueurs))) {
            echo '<p>Un joueur ne peut pas être sélectionné plusieurs fois.</p>';
            exit;
        }

        $requete = $linkpdo->prepare("
            INSERT INTO participer (idJoueur, Date_heure_match, Role_titulaire, Poste)
            VALUES (:idJoueur, :Date_heure_match, :Role_titulaire, :Poste)
        ");

        foreach ($joueurs as $index => $idJoueur) {
            if (!empty($idJoueur)) {
                $role = $roles[$index];
                $roleTitulaire = ($role !== 'Remplaçant') ? 1 : 0;

                $requete->execute([
                    ':idJoueur' => $idJoueur,
                    ':Date_heure_match' => $dateHeureMatch,
                    ':Role_titulaire' => $roleTitulaire,
                    ':Poste' => $role
                ]);
            }
        }

        echo "<p>Sélection des joueurs enregistrée avec succès pour le match du $dateHeureMatch.</p>";
        echo '<a href="feuille_match.php">Retour à la sélection des matchs</a>';
    }
    ?>
</body>
</html>
