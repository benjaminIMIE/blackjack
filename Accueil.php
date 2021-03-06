		<?php require('head.php'); ?>
		<?php 

			$_SESSION['gameStatus'] = true;
			$_SESSION['double'] = false;
			$_SESSION['double_1'] = false;
			$_SESSION['double_2'] = false;
			$_SESSION['split'] = false;
			$_SESSION['FirstChoice'] = true;
			$_SESSION['FirstChoice_1'] = true;
			$_SESSION['FirstChoice_2'] = true;
			$_SESSION['first_game'] = true;
			$_SESSION['second_game'] = true;

			$DisplayAccounts = $bdd->prepare("SELECT account FROM players WHERE Player_Name = ? AND MDP = ?");
			$DisplayAccounts->bindParam('1', $_SESSION['pseudo']);
			$DisplayAccounts->bindParam('2', $_SESSION['mdp']);

			

			?>
			<h2>Cliquez sur Jouer pour lancer une partie !</h2>
				<?php 
					$DisplayAccounts->execute(); 
					while ($DisplayAccount = $DisplayAccounts->fetch()) {
						if ($DisplayAccount[0] <= 0) {
						 	header('location:looser.php');
						}

						?>

						<div id="formulaires">
							<form action="lancement_partie.php" method="POST" id="accueil">
								<div id="input_mise">
									<label for="mise">Votre mise :</label>
									<input type="number" name="bet" id="mise" min="1" max=<?php echo ($DisplayAccount[0] / 2); ?> step="1" value="10" />
								</div>
								<input type="submit" name="valider" value="Jouer"/>
							</form>
						</div>
					<?php }	

			if (isset($_POST['again'])) {
				$truncateSabot = $bdd->prepare("TRUNCATE TABLE sabot");
				$truncateGame = $bdd->prepare("TRUNCATE TABLE game");
	    		$truncateSabot->execute();
	    		$truncateGame->execute();
			}

			$UpdateAccounts = $bdd->prepare("UPDATE players SET account = ? WHERE Player_Name = ? AND MDP = ?");
			$UpdateAccounts->bindParam('1', $newAmount);
			$UpdateAccounts->bindParam('2', $_SESSION['pseudo']);
			$UpdateAccounts->bindParam('3', $_SESSION['mdp']);

			if (isset($_POST['low'])) {
				$truncateSabot = $bdd->prepare("TRUNCATE TABLE sabot");
				$truncateGame = $bdd->prepare("TRUNCATE TABLE game");
	    		$truncateSabot->execute();
	    		$truncateGame->execute();

	    		$newAmount = 100;
	    		$UpdateAccounts->execute();
				header("Refresh:0");
			} elseif (isset($_POST['medium'])) {
				$truncateSabot = $bdd->prepare("TRUNCATE TABLE sabot");
				$truncateGame = $bdd->prepare("TRUNCATE TABLE game");
	    		$truncateSabot->execute();
	    		$truncateGame->execute();

	    		$newAmount = 500;
	    		$UpdateAccounts->execute();
				header("Refresh:0");
			} elseif (isset($_POST['high'])) {
				$truncateSabot = $bdd->prepare("TRUNCATE TABLE sabot");
				$truncateGame = $bdd->prepare("TRUNCATE TABLE game");
	    		$truncateSabot->execute();
	    		$truncateGame->execute();

	    		$newAmount = 1000;
	    		$UpdateAccounts->execute();
				header("Refresh:0");
			}

			$clean_Split = $bdd->prepare("DELETE FROM split");
			$clean_Split->execute();

require('footer.php'); ?>