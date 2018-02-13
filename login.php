<?php require('head.php'); ?>
	<div id="formulaires">
		<form action="" method="POST" id="inscription">
			<div>
				<img src="img/jetons01.png"/>
				<h2>Inscription</h2>
				<input type="text" name="pseudo" placeholder="Votre Pseudo"/>
				<input type="password" name="mdp" placeholder="Votre mot de passe"/>
				<input type="submit" name="create" value="S'enregistrer"/>
			</div>
		</form>
		<form action="" method="POST" id="connexion">
			<div>
				<img src="img/blackjack.png"/>
				<h2>Connexion</h2>
				<input type="text" name="pseudo" placeholder="Votre Pseudo"/>
				<input type="password" name="mdp" placeholder="Votre mot de passe"/>
				<input type="submit" name="connect" value="Se connecter"/>
			</div>
		</form>
	</div>
	<?php 
	// Connexion Form
	if (isset($_POST['connect']) && !empty($_POST['pseudo'])) {
			$pseudo = $_POST['pseudo'];
			$MDP = $_POST['mdp'];

			$login = $bdd->prepare("SELECT COUNT(*) AS nbr FROM players WHERE Player_Name = ? AND MDP = ?");
			$login->bindParam('1', $pseudo);
			$login->bindParam('2', $MDP);
			$login->execute();

			$connect = $login->fetchColumn();

			if ($connect < 1){
				echo '<p id="WarningAccount">Ce compte n\'existe pas... Merci de recommencer.</p>';
			} else {
				$_SESSION['pseudo'] = $pseudo;
				$_SESSION['mdp'] = $MDP;

				$account = $bdd->prepare("SELECT account FROM players WHERE Player_Name = ? AND MDP = ?");
				$account->bindParam('1', $pseudo);
				$account->bindParam('2', $MDP);
				$account->execute();

				while ($starting_Account = $account->fetch()) {
					$_SESSION['starting_Account'] = $starting_Account[0];
				}

				header('location:Accueil.php');
			}
			$login -> CloseCursor();
	} 
	// Subscribe Form
	if (isset($_POST['create']) && !empty($_POST['pseudo'])) {
			$pseudo = $_POST['pseudo'];
			$_SESSION['pseudo'] = $pseudo;
			$MDP = $_POST['mdp'];
			$_SESSION['mdp'] = $MDP;

			$login = $bdd->prepare("SELECT COUNT(*) AS nbr FROM players WHERE Player_Name = ? AND MDP = ?");
			$login->bindParam('1', $pseudo);
			$login->bindParam('2', $MDP);
			$login->execute();

			$connect = $login->fetchColumn();

			if ($connect < 1){
				$account = 100;
				$inscription = $bdd->prepare('INSERT INTO players (Player_Name, MDP, Account) VALUES (?,?,?)');
				$inscription->bindParam('1',$pseudo);
				$inscription->bindParam('2',$MDP);
				$inscription->bindParam('3',$account);
				$inscription->execute();

				echo '<div id="WarningAccount"><p>Votre compte vient d\'être créé, et est crédité de <span>' . $account . '</span> BitCoins </p> 
				<p>Vous allez maintenant être redirigé</p></div>';
				header( "Refresh:5; url=Accueil.php", true, 60*3);
			} else {

				echo '<p id="WarningAccount">Ce compte existe déjà</p>';
			}
			$login -> CloseCursor();
	}

	?>

<?php require('footer.php'); ?>