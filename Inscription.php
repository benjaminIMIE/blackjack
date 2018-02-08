<?php require('head.php'); ?>
<?php require('connexion.php'); ?>

	<header>
		<img src="blackjack-logo.jpg" alt="BlackJack logo"/>
	</header>

	<form action="" method="POST" id="connexion">
		<!-- <input type="submit" name"> -->
		<div>
			<input type="text" name="pseudo" placeholder="Votre Pseudo"/>
			<input type="password" name="mdp" placeholder="Votre mot de passe"/>
		</div>
		<input type="submit" name="connect" value="S'enregistrer"/>
	</form>

	<?php 
	session_start();

	if (isset($_POST['connect']) && !empty($_POST['pseudo'])) {

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

				echo '<p id="WarningAccount">Compte créé, merci de votre confiance. Votre compte est crédité de ' . $account . ' BitCoins <br/> 
				Vous allez être redirigé</p>';
				header( "Refresh:5; url=Accueil.php", true, 60*3);
			} else {

				echo '<p id="WarningAccount">Ce compte existe déjà</p>';
			}
			$login -> CloseCursor();

	} ?>



<?php require('footer.php'); ?>