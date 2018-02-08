<?php require('head.php'); ?>
<?php 
	
	// Money Verif. If player don't have any money left, he'll be redirect to Looser.php
	$DisplayAccounts = $bdd->prepare("SELECT account FROM players WHERE Player_Name = ? AND MDP = ?");
	$DisplayAccounts->bindParam('1', $_SESSION['pseudo']);
	$DisplayAccounts->bindParam('2', $_SESSION['mdp']);

	$DisplayAccounts->execute(); 
	while ($DisplayAccount = $DisplayAccounts->fetch()) {
		if ($DisplayAccount[0] <= 0) {
		 	header('location:looser.php');
		}
	}

	// Score system
	$Player_Score = 0;
	$Dealer_Score = 0;

	$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
	$Player_Score_Query->bindParam('1', $Player_Name);

	$Player_Name = 'Player';
	$Player_Score_Query->execute();

	while ($Player_Points = $Player_Score_Query->fetch()) {
		$Player_Score = $Player_Points[0];
	} 

	$Player_Name = 'Dealer';
	$Player_Score_Query->execute();

	while ($Player_Points = $Player_Score_Query->fetch()) {
		$Dealer_Score = $Player_Points[0];
	} 

	$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM split WHERE Position = ?");
	$Player_Score_Query->bindParam('1', $Position);
	$Position = 'left';
	$Player_Score_Query->execute();

	while ($Player_Points = $Player_Score_Query->fetch()) {
		$Player_Score_Left = $Player_Points[0];
	} 

	$Position = 'right';
	$Player_Score_Query->execute();
	
	while ($Player_Points = $Player_Score_Query->fetch()) {
		$Player_Score_Right = $Player_Points[0];
	} 

	$DisplayAccounts = $bdd->prepare("SELECT account FROM players WHERE Player_Name = ? AND MDP = ?");
	$DisplayAccounts->bindParam('1', $_SESSION['pseudo']);
	$DisplayAccounts->bindParam('2', $_SESSION['mdp']);

	$UpdateAccounts = $bdd->prepare("UPDATE players SET account = ? WHERE Player_Name = ? AND MDP = ?");
	$UpdateAccounts->bindParam('1', $newAmount);
	$UpdateAccounts->bindParam('2', $_SESSION['pseudo']);
	$UpdateAccounts->bindParam('3', $_SESSION['mdp']);

	?>

	<?php
		// Checking BlackJack
		$CountCardPlayer = $bdd->prepare ("SELECT COUNT(*) AS nbr FROM game WHERE Player_Name = 'Player'");
		$CountCardPlayer->execute();
		$blackjackPlayer = $CountCardPlayer->fetchColumn();

		$CountCardDealer = $bdd->prepare ("SELECT COUNT(*) AS nbr FROM game WHERE Player_Name = 'Dealer'");
		$CountCardDealer->execute();
		$blackjackDealer = $CountCardDealer->fetchColumn();

		if ($blackjackPlayer == 2 && $Player_Score == 21 && $_SESSION['gameStatus'] != false) {
			$_SESSION['gameStatus'] = false;
			header("Refresh:0");
		}elseif ($blackjackDealer == 2 && $Dealer_Score == 21 && $_SESSION['gameStatus'] != false) {
			$_SESSION['gameStatus'] = false;
			header("Refresh:0");
		} 

		// Score compare
		if (isset($_SESSION['gameStatus']) && $_SESSION['gameStatus'] == false) { ?>
			<div id="popup_end">
				<div id="endgame">
					<?php 
					if ($_SESSION['split'] == true) {
						$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM split WHERE Position = ?");
						$Player_Score_Query->bindParam('1', $Position);
						$Position = 'left';
						$Player_Score_Query->execute();

						while ($Player_Points = $Player_Score_Query->fetch()) {
							$Player_Score_Left = $Player_Points[0];
						} 

						$Position = 'right';
						$Player_Score_Query->execute();
						
						while ($Player_Points = $Player_Score_Query->fetch()) {
							$Player_Score_Right = $Player_Points[0];
						} 

						// Dealer won both games
						if ( ($Player_Score_Left > 21 OR ($Dealer_Score >= $Player_Score_Left AND $Dealer_Score <= 21)) && 
							 ($Player_Score_Right > 21 OR ($Dealer_Score >= $Player_Score_Right AND $Dealer_Score <= 21))  ){
							if ($_SESSION['double_1'] == false && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Défaite</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']) - ($_SESSION['bet']);

									echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == true && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Défaite</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']*2) - ($_SESSION['bet']);

									echo "<p>Vous venez de perdre deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == false && $_SESSION['double_2'] == true) {
								$DisplayAccounts->execute(); 
								echo "<p>Défaite</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']) - ($_SESSION['bet']*2);

									echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} else {
								$DisplayAccounts->execute(); 
								echo "<p>Défaite</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']*2) - ($_SESSION['bet']*2);

									echo "<p>Vous venez de perdre deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							}
							
						} // Player won left game
						elseif ( ($Player_Score_Left > $Dealer_Score AND $Player_Score_Left <= 21) && 
							 ($Player_Score_Right > 21 OR ($Dealer_Score >= $Player_Score_Right AND $Dealer_Score <= 21))  ){
							if ($_SESSION['double_1'] == false && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Egalité</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']) - ($_SESSION['bet']);

									echo "<p>Vous venez de gagner votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous perdez quand même votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == true && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Plus victoire que défaite !</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']*2) - ($_SESSION['bet']);

									echo "<p>Vous venez de gagner deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous perdez quand même votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == false && $_SESSION['double_2'] == true) {
								$DisplayAccounts->execute(); 
								echo "<p>Plus défaite que victoire...</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']) - ($_SESSION['bet']*2);

									echo "<p>Vous venez de gagner votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous perdez quand même deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} else {
								$DisplayAccounts->execute(); 
								echo "<p>Egalité</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']*2) - ($_SESSION['bet']*2);

									echo "<p>Vous venez de gagner deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous perdez quand même deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							}

						}// Player won right game
						elseif ( ($Player_Score_Left > 21 OR ($Dealer_Score >= $Player_Score_Left AND $Dealer_Score <= 21)) && 
							     ($Player_Score_Right > $Dealer_Score AND $Player_Score_Right <= 21) ){
							if ($_SESSION['double_1'] == false && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Egalité</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']) + ($_SESSION['bet']);

									echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous gagnez quand même votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == true && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Plus défaite que victoire...</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']*2) + ($_SESSION['bet']);

									echo "<p>Vous venez de perdre deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous gagnez quand même votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == false && $_SESSION['double_2'] == true) {
								$DisplayAccounts->execute(); 
								echo "<p>Plus victoire que défaite !</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']) + ($_SESSION['bet']*2);

									echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous gagnez quand même deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} else {
								$DisplayAccounts->execute(); 
								echo "<p>Egalité</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] - ($_SESSION['bet']*2) + ($_SESSION['bet']*2);

									echo "<p>Vous venez de perdre deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, mais vous gagnez quand même deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							}

						}// Player won both games
						elseif ( ($Player_Score_Left > $Dealer_Score AND $Player_Score_Left <= 21) && 
							     ($Player_Score_Right > $Dealer_Score AND $Player_Score_Right <= 21) ){
							if ($_SESSION['double_1'] == false && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Double Victoire !</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']) + ($_SESSION['bet']);

									echo "<p>Vous venez de gagner votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == true && $_SESSION['double_2'] == false) {
								$DisplayAccounts->execute(); 
								echo "<p>Grosse victoire !!</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']*2) + ($_SESSION['bet']);

									echo "<p>Vous venez de gagner deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} elseif ($_SESSION['double_1'] == false && $_SESSION['double_2'] == true) {
								$DisplayAccounts->execute(); 
								echo "<p>Groose victoire !!</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']) + ($_SESSION['bet']*2);

									echo "<p>Vous venez de gagner votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							} else {
								$DisplayAccounts->execute(); 
								echo "<p>\o/ YOUHOU \o/</p>";
								while ($DisplayAccount = $DisplayAccounts->fetch()) {
									$newAmount = $DisplayAccount[0] + ($_SESSION['bet']*2) + ($_SESSION['bet']*2);

									echo "<p>Vous venez de gagner deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins sur la partie de gauche, ainsi que deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> sur la partie de droite. </p>";
									echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

									$UpdateAccounts->execute();
								}
							}

						}
					} else {
						if (($blackjackDealer == 2 && $Dealer_Score == 21) && $_SESSION['double'] == false) { // Blackjack for Dealer
							$DisplayAccounts->execute(); 
							echo "<p>La banque réalise un Blackjack !</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$newAmount = $DisplayAccount[0] - ($_SESSION['bet'] * 3 / 2);

								echo "<p>Vous perdez directement 1,5 fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif (($blackjackDealer == 2 && $Dealer_Score == 21) && $_SESSION['double'] == true) { // Blackjack for Dealer & Player double his bet
							$DisplayAccounts->execute(); 
							echo "<p>La banque réalise un Blackjack !</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$double = $_SESSION['bet'] * 2;
								$newAmount = $DisplayAccount[0] - ($double * 3 / 2);

								echo "<p>Vous perdez directement 1,5 fois votre mise de <span> " . $double . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif (($Player_Score > 21 OR ($Dealer_Score >= $Player_Score AND $Dealer_Score <= 21)) && $_SESSION['double'] == false) { // Dealer won
							$DisplayAccounts->execute(); 
							echo "<p>Défaite</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$newAmount = $DisplayAccount[0] - $_SESSION['bet'];

								echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif (($Player_Score > 21 OR ($Dealer_Score >= $Player_Score AND $Dealer_Score <= 21)) && $_SESSION['double'] == true) { // Dealer won & Player double his bet
							$DisplayAccounts->execute(); 
							echo "<p>Défaite</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$double = $_SESSION['bet'] * 2;
								$newAmount = $DisplayAccount[0] - $double;

								echo "<p>Vous venez de perdre deux fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif (($blackjackPlayer == 2 && $Player_Score == 21) && $_SESSION['double'] == false) { // Blackjack for Player
							$DisplayAccounts->execute(); 
							echo "<p>Blackjack !</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$newAmount = $DisplayAccount[0] + ($_SESSION['bet'] * 3);

								echo "<p>Vous gagnez directement 3 fois votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif (($blackjackPlayer == 2 && $Player_Score == 21) && $_SESSION['double'] == true) { // Blackjack for Player & he double his bet
							$DisplayAccounts->execute(); 
							echo "<p>Blackjack !</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$double = $_SESSION['bet'] * 2;
								$newAmount = $DisplayAccount[0] + ($double * 3);

								echo "<p>Vous gagnez directement 3 fois votre mise de <span> " . $double . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif ((($Player_Score > $Dealer_Score AND $Player_Score <= 21) OR $Dealer_Score > 21) && $_SESSION['double'] == false) { // Player won
							$DisplayAccounts->execute(); 
							echo "<p>Victoire</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$newAmount = $DisplayAccount[0] + $_SESSION['bet'];

								echo "<p>Vous venez de gagner votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						} elseif ((($Player_Score > $Dealer_Score AND $Player_Score <= 21) OR $Dealer_Score > 21) && $_SESSION['double'] == true) { // Player won & he double his bet
							$DisplayAccounts->execute(); 
							echo "<p>Victoire</p>";
							while ($DisplayAccount = $DisplayAccounts->fetch()) {
								$double = $_SESSION['bet'] * 2;
								$newAmount = $DisplayAccount[0] + $double;

								echo "<p>Vous venez de doubler votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
								echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";

								$UpdateAccounts->execute();
							}
						}
					} ?>
				</div>
				<?php // Check if player have enough money to play again
					if ($newAmount <= 0) {
						header( "Refresh:5; url=looser.php", true, 60*3);
					} else { ?>
						<form action="Accueil.php" method="POST" id="again">
						 	<input type="submit" name="again" value="Rejouer"/>
						</form>
					<?php } ?>
			</div>
		<?php } elseif ($Player_Score > 21 && $_SESSION['gameStatus'] != false) { // Player lost
			$_SESSION['gameStatus'] = false; ?>
			<div id="popup_end">
				<p id="endgame">
					<?php
						$DisplayAccounts->execute(); 
						echo "<p>Défaite</p>";
						while ($DisplayAccount = $DisplayAccounts->fetch()) {
							$newAmount = $DisplayAccount[0] - $_SESSION['bet'];
							echo "<p>Vous venez de perdre votre mise de <span> " . $_SESSION['bet'] . " </span> BitCoins </p>";
							echo "<p>Votre nouveau solde est de : <span> " . $newAmount . "</span></p>";
							$UpdateAccounts->execute();
						}
						header("Refresh:0");
					?>
				</p>
				<form action="Accueil.php" method="POST" id="again">
				 	<input type="submit" name="again" value="Rejouer"/>
				</form>
			</div>
		<?php }	
		$_SESSION['newAmount'] = $newAmount;
		?>



	<h1>Votre mise initiale est de : 
		<?php
			echo $_SESSION['bet'] . ' BitCoins';
		?>
	</h1>


	<?php 	 

		// Select cards from 'Sabot'
		$query = $bdd->prepare("SELECT * FROM sabot ");
		// Remove card after draw
		$deleteCard = $bdd->prepare("DELETE FROM sabot WHERE ID = ?");
		$deleteCard->bindParam('1', $Card_ID);

		$game = $bdd->prepare("INSERT INTO game (Card_Name, Card_Value, Card_Number, Player_Name) VALUES (?,?,?,?)");
		$game->bindParam('1', $Card_Name);
		$game->bindParam('2', $Card_Value);
		$game->bindParam('3', $Card_Number);
		$game->bindParam('4', $Player_Name);

		// Display Player Cards
		$Player_Cards = $bdd->prepare("SELECT * FROM game WHERE Player_Name = 'Player'");
		$Player_Cards->execute();

		// Game settings
		if (isset($_POST['hit'])) { // Player press "Hit" button
			$_SESSION['FirstChoice'] = false;
			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_Number = $sabot[0]['Card_Number'];
			$Card_ID = $sabot[0]['ID'];
			
			// Add card in the Game Table
			$Player_Name = 'Player';
			$game->execute();
			echo $Player_Score;

			if ($Player_Score <= 21 && $Card_Value == 1 && (($Player_Score + 10) <= 21) ) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
			} elseif ($Player_Score > 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$Card_Value = 1;
				$CheckAs->execute();
			}

			// echo "<p>Le joueur tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();
			// header("Refresh:0");
		} elseif (isset($_POST['double'])) { // Player press "Double" button
			$_SESSION['FirstChoice'] = false;
			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_Number = $sabot[0]['Card_Number'];
			$Card_ID = $sabot[0]['ID'];
			
			// Add card in the Game Table
			$Player_Name = 'Player';
			$game->execute();

			// Check As
			if ($Player_Score <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
			} else {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 1;
				$CheckAs->execute();
			}

			// echo "<p>Le joueur tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();

			// Set the Player's Score
			$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
			$Player_Score_Query->bindParam('1', $Player_Name);
			$Player_Score_Query->execute();

			while ($Player_Points = $Player_Score_Query->fetch()) {
				$Player_Score = $Player_Points[0];
			} 

			// Dealer's Turn
			while ($Dealer_Score <= 16 AND $Player_Score <= 21) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_Number = $sabot[0]['Card_Number'];
				$Card_ID = $sabot[0]['ID'];

				// Add card in the Game Table
				$Player_Name = 'Dealer';
				$game->execute();

				// Check As
				if ($Dealer_Score <= 21 && $Card_Value == 1 && ($Dealer_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ? AND Card_ID = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Player_Name);
					$CheckAs->bindParam('3', $Card_ID);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// echo "<p>Le dealer tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

				// Remove card after draw
				$deleteCard->execute();

				// Set the Dealer's Score
				$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				$Player_Score_Query->bindParam('1', $Player_Name);

				$Player_Name = 'Dealer';
				$Player_Score_Query->execute();

				while ($Player_Points = $Player_Score_Query->fetch()) {
					$Dealer_Score = $Player_Points[0];
				} 
			}
			$_SESSION['gameStatus'] = false;
			$_SESSION['double'] = true;
			header("Refresh:0");
		} elseif (isset($_POST['stand'])) { // Player press "Stand" button
			while ($Dealer_Score <= 16) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_Number = $sabot[0]['Card_Number'];
				$Card_ID = $sabot[0]['ID'];

				// Add card in the Game Table
				$Player_Name = 'Dealer';
				$game->execute();

				// Check As
				if ($Dealer_Score <= 21 && $Card_Value == 1 && ($Dealer_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ?AND Card_ID = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Player_Name);
					$CheckAs->bindParam('3', $Card_ID);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// echo "<p>Le dealer tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

				// Remove card after draw
				$deleteCard->execute();

				// Set the Dealer's Score
				$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				$Player_Score_Query->bindParam('1', $Player_Name);

				$Player_Name = 'Dealer';
				$Player_Score_Query->execute();

				while ($Player_Points = $Player_Score_Query->fetch()) {
					$Dealer_Score = $Player_Points[0];
				} 

				header("Refresh:0");

			}
			$_SESSION['gameStatus'] = false;
		} elseif (isset($_POST['split'])) { // Player press "Split" button
			$_SESSION['split'] = true;
			$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
			$split->bindParam('1', $Card_Name);
			$split->bindParam('2', $Card_Value);
			$split->bindParam('3', $Card_ID);
			$split->bindParam('4', $Position);

			// Transert Cards from "Game" TO "Split" able
			$Player_Cards->execute();
			$i=0;
			while ($Player_Card = $Player_Cards->fetch()) {
				$Card_Name = $Player_Card[0];
				$Card_Value = $Player_Card[1];
				$Card_ID = $Player_Card[2];
				if ($i == 0) {
					$Position = "left";
				} else {
					$Position = "right";
				}
				$split->execute();
				$i++;
			}

			// AS back to 11 points
			$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = 11 WHERE Card_Value = 1 AND Card_ID = 1");
			$CheckAs->execute();

			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			// Add card in the Game Table
			$Player_Name = 'Player';
			$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
			$split->bindParam('1', $Card_Name);
			$split->bindParam('2', $Card_Value);
			$split->bindParam('3', $Card_ID);
			$split->bindParam('4', $Position);
			
			// First Card
			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_ID = $sabot[0]['ID'];
			$Position = 'left';
			$split->execute();

			//check AS
			if ($Player_Score <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
			} else {
				$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 1;
				$CheckAs->execute();
			}

			// Remove card after draw
			$deleteCard->execute();
			
			// Second Card
			$Card_Name = $sabot[1]['Card_Name'];
			$Card_Value = $sabot[1]['Card_Value'];
			$Card_ID = $sabot[1]['ID'];
			$Position = 'right';
			$split->execute();

			//check AS
			if ($Player_Score <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
			} else {
				$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 1;
				$CheckAs->execute();
			}

			// echo "<p>Le joueur tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();
		} 

		if (isset($_POST['hit_1'])) { // Player press "Hit" button
			$_SESSION['FirstChoice_1'] = false;
			if ($_SESSION['split'] == true) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				// Add card in the Game Table
				$Player_Name = 'Player';
				$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
				$split->bindParam('1', $Card_Name);
				$split->bindParam('2', $Card_Value);
				$split->bindParam('3', $Card_ID);
				$split->bindParam('4', $Position);
				
				// First Card
				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_ID = $sabot[0]['ID'];
				$Position = 'left';
				$split->execute();

				//check AS
				if ($Player_Score_Left <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 11;
					$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// Remove card after draw
				$deleteCard->execute();
				if ($Player_Score > 21) {

				}
			}
			header("Refresh:0");
		} elseif (isset($_POST['double_1'])) { // Player press "Double" button
			if ($_SESSION['split'] == true) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				// Add card in the Game Table
				$Player_Name = 'Player';
				$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
				$split->bindParam('1', $Card_Name);
				$split->bindParam('2', $Card_Value);
				$split->bindParam('3', $Card_ID);
				$split->bindParam('4', $Position);
				
				// First Card
				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_ID = $sabot[0]['ID'];
				$Position = 'left';
				$split->execute();

				//check AS
				if ($Player_Score_Left <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 11;
					$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// Remove card after draw
				$deleteCard->execute();

				// Set the Player's Score
				$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				$Player_Score_Query->bindParam('1', $Player_Name);
				$Player_Score_Query->execute();

				while ($Player_Points = $Player_Score_Query->fetch()) {
					$Player_Score = $Player_Points[0];
				} 

					$_SESSION['first_game'] = false;
					$_SESSION['double_1'] = true;
					header("Refresh:0");
				}
		} elseif (isset($_POST['stand_1'])) { // Player press "Stand" button
			$_SESSION['first_game'] = false;
		} 
		if (isset($_POST['hit_2'])) { // Player press "Hit" button
			$_SESSION['FirstChoice_2'] = false;
			if ($_SESSION['split'] == true) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				// Add card in the Game Table
				$Player_Name = 'Player';
				$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
				$split->bindParam('1', $Card_Name);
				$split->bindParam('2', $Card_Value);
				$split->bindParam('3', $Card_ID);
				$split->bindParam('4', $Position);
				
				// First Card
				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_ID = $sabot[0]['ID'];
				$Position = 'right';
				$split->execute();

				//check AS
				if ($Player_Score_Right <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 11;
					$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// Remove card after draw
				$deleteCard->execute();
			}
			header("Refresh:0");
		} elseif (isset($_POST['double_2'])) { // Player press "Double" button
			if ($_SESSION['split'] == true) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				// Add card in the Game Table
				$Player_Name = 'Player';
				$split = $bdd->prepare("INSERT INTO split (Card_Name, Card_Value, Card_ID, Position) VALUES (?,?,?,?)");
				$split->bindParam('1', $Card_Name);
				$split->bindParam('2', $Card_Value);
				$split->bindParam('3', $Card_ID);
				$split->bindParam('4', $Position);
				
				// First Card
				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_ID = $sabot[0]['ID'];
				$Position = 'right';
				$split->execute();

				//check AS
				if ($Player_Score_Right <= 21 && $Card_Value == 1 && ($Player_Score + 11) <= 21) {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 11;
					$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE split SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ? AND Position = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Card_ID);
					$CheckAs->bindParam('3', $Position);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// Remove card after draw
				$deleteCard->execute();

				// Set the Player's Score
				$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				$Player_Score_Query->bindParam('1', $Player_Name);
				$Player_Score_Query->execute();

				while ($Player_Points = $Player_Score_Query->fetch()) {
					$Player_Score = $Player_Points[0];
				} 

				// Dealer Turn
				// while ($Dealer_Score <= 16 AND $Player_Score <= 21) {
				// 	$query->execute();
				// 	$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				// 	$Card_Name = $sabot[0]['Card_Name'];
				// 	$Card_Value = $sabot[0]['Card_Value'];
				// 	$Card_Number = $sabot[0]['Card_Number'];
				// 	$Card_ID = $sabot[0]['ID'];

				// 	// Add card in the Game Table
				// 	$Player_Name = 'Dealer';
				// 	$game->execute();

				// 	// Check As
				// 	if ($Dealer_Score <= 21 && $Card_Value == 1 && ($Dealer_Score + 11) <= 21) {
				// 	$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				// 	$CheckAs->bindParam('1', $Card_Value);
				// 	$CheckAs->bindParam('2', $Player_Name);
				// 	$CheckAs->bindParam('3', $Card_ID);
				// 	$Card_Value = 11;
				// 	$CheckAs->execute();
				// 	} else {
				// 		$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ? AND Card_ID = ?");
				// 		$CheckAs->bindParam('1', $Card_Value);
				// 		$CheckAs->bindParam('2', $Player_Name);
				// 		$CheckAs->bindParam('3', $Card_ID);
				// 		$Card_Value = 1;
				// 		$CheckAs->execute();
				// 	}

				// 	// echo "<p>Le dealer tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

				// 	// Remove card after draw
				// 	$deleteCard->execute();

				// 	// Set the Dealer's Score
				// 	$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				// 	$Player_Score_Query->bindParam('1', $Player_Name);

				// 	$Player_Name = 'Dealer';
				// 	$Player_Score_Query->execute();

				// 	while ($Player_Points = $Player_Score_Query->fetch()) {
				// 		$Dealer_Score = $Player_Points[0];
				// 	} 

					// $_SESSION['gameStatus'] = false;
					$_SESSION['double_2'] = true;
					$_SESSION['second_game'] = false;
					header("Refresh:0");
				// }
			}
		} elseif (isset($_POST['stand_2'])) { // Player press "Stand" button
			$_SESSION['second_game'] = false;
		} 

		if ($_SESSION['first_game'] == false && $_SESSION['second_game'] == false) {
			while ($Dealer_Score <= 16) {
				$query->execute();
				$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

				$Card_Name = $sabot[0]['Card_Name'];
				$Card_Value = $sabot[0]['Card_Value'];
				$Card_Number = $sabot[0]['Card_Number'];
				$Card_ID = $sabot[0]['ID'];

				// Add card in the Game Table
				$Player_Name = 'Dealer';
				$game->execute();

				// Check As
				if ($Dealer_Score <= 21 && $Card_Value == 1 && ($Dealer_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Player_Name = ? AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Player_Name);
				$CheckAs->bindParam('3', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
				} else {
					$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Player_Name = ?AND Card_ID = ?");
					$CheckAs->bindParam('1', $Card_Value);
					$CheckAs->bindParam('2', $Player_Name);
					$CheckAs->bindParam('3', $Card_ID);
					$Card_Value = 1;
					$CheckAs->execute();
				}

				// echo "<p>Le dealer tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

				// Remove card after draw
				$deleteCard->execute();

				// Set the Dealer's Score
				$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM game WHERE Player_Name = ?");
				$Player_Score_Query->bindParam('1', $Player_Name);

				$Player_Name = 'Dealer';
				$Player_Score_Query->execute();

				while ($Player_Points = $Player_Score_Query->fetch()) {
					$Dealer_Score = $Player_Points[0];
				} 

				header("Refresh:0");

			}
			$_SESSION['gameStatus'] = false;
		}

		?>
		<div class="players_container">
			<div class="player">
				<?php echo "<h2>Cartes du joueur</h2>";	?>
				<div class="cards_container">
					<?php // Display Player's Cards
					if (isset($_POST['split']) || $_SESSION['split'] == true) {
						// Faire la query pour selectionner que le bon côté.
						$Left_Cards = $bdd->prepare("SELECT * FROM split WHERE Position = 'left' ");
						$Left_Cards->execute();
						echo "<div id='Left_Cards'>";
						while ($Left_Card = $Left_Cards->fetch()) {
							echo "<img src=img/" . $Left_Card['Card_Name'] . ".svg alt=" . $Left_Card['Card_Name'] . "/>";
						}
						echo "</div>";

						$Right_Cards = $bdd->prepare("SELECT * FROM split WHERE Position = 'right' ");
						$Right_Cards->execute();
						echo "<div id='Right_Cards'>";
						while ($Right_Card = $Right_Cards->fetch()) {
							echo "<img src=img/" . $Right_Card['Card_Name'] . ".svg alt=" . $Right_Card['Card_Name'] . "/>";
						}
						echo "</div>";
					} else {
						$Player_Cards->execute();
						$i=0;
						while ($Player_Card = $Player_Cards->fetch()) {
							echo "<img src=img/" . $Player_Card['Card_Name'] . ".svg alt=" . $Player_Card['Card_Name'] . "/>";
							if ($i == 0) {
							 	$_SESSION['first_Card'] = $Player_Card['Card_Number'];
							} else {
								$_SESSION['second_Card'] = $Player_Card['Card_Number'];
							}
							$i++;
						} 
					} ?>
				</div>
			</div>
			<div class="player dealer">
				<?php // Display Dealer Cards
				$Dealer_Cards = $bdd->prepare("SELECT * FROM game WHERE Player_Name = 'Dealer'");
				$Dealer_Cards->execute();
				echo "<h2>Cartes du dealer</h2>" ;
				?>
				<div class="cards_container">
					<?php
					while ($Dealer_Card = $Dealer_Cards->fetch()) {
						echo "<img src=img/" . $Dealer_Card['Card_Name'] . ".svg alt=" . $Dealer_Card['Card_Name'] . "/>";
					} ?>
				</div>
			</div>
			<div id="score">
				<?php
				// Score display
				echo "<h2>Score</h2>";
				// Display Player's Score
				if ($_SESSION['split'] == true) {
					$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM split WHERE Position = 'left'");
					$Player_Score_Query->execute();

					while ($Player_Points = $Player_Score_Query->fetch()) {
						$Player_Score = $Player_Points[0];
					} 
					echo "<p>Score actuel partie de gauche : " . $Player_Score;

					$Player_Score_Query = $bdd->prepare("SELECT SUM(Card_Value) FROM split WHERE Position = 'right'");
					$Player_Score_Query->execute();

					while ($Player_Points = $Player_Score_Query->fetch()) {
						$Player_Score = $Player_Points[0];
					} 
					echo "<p>Score actuel partie de droite : " . $Player_Score;
				} else {
					echo "<p>Score actuel pour le joueur : " . $Player_Score . '</p>';
				}
				// Display Dealer's Score
				echo "<p>Score actuel pour le dealer : " . $Dealer_Score . '</p>';

				?>
			</div>
		</div>

		<?php // Forms
		if (isset($_SESSION['gameStatus']) && $_SESSION['gameStatus'] == false && $_SESSION['split'] == false) {
			?>
			 <form action="" method="POST">
			 	<input type="submit" name="stand" value="Rester" disabled="disabled" />
			 	<input type="submit" name="hit" value="Tirer une carte" disabled="disabled"/>
			 	<input type="submit" name="double" value="Doubler" disabled="disabled" />
			 </form>
			<?php 
		} elseif ($_SESSION['split'] == true) {
			if ($_SESSION['first_game'] == false && $_SESSION['second_game'] == false) { ?>
				<form action="" method="POST">
				 	<div id="first_game">
			 			<p>1ère partie</p>
					 	<input type="submit" name="stand_1" value="Rester" disabled="disabled"/>
					 	<input type="submit" name="hit_1" value="Tirer une carte" disabled="disabled"/>
				 	</div>
				 	<div id="second_game">
				 		<p>2ème partie</p>
						<input type="submit" name="stand_2" value="Rester" disabled="disabled" />
					 	<input type="submit" name="hit_2" value="Tirer une carte" disabled="disabled"/>
				 	</div>
				</form>
			<?php } elseif ($_SESSION['first_game'] == false) { ?>
				<form action="" method="POST">
				 	<div id="first_game">
			 			<p>1ère partie</p>
						<input type="submit" name="stand_1" value="Rester" disabled="disabled" />
					 	<input type="submit" name="hit_1" value="Tirer une carte" disabled="disabled"/>
				 	</div>
				 	<div id="second_game">
				 		<p>2ème partie</p>
					 	<input type="submit" name="stand_2" value="Rester"/>
					 	<input type="submit" name="hit_2" value="Tirer une carte"/>
					 	<?php
							if ($_SESSION['first_Card'] == $_SESSION['second_Card'] && $_SESSION['FirstChoice_2'] == true) {
								echo '<input type="submit" name="double_2" value="Doubler" />';
							}
					 	?>
				 	</div>
				</form>
			<?php } elseif ($_SESSION['second_game'] == false) { ?>
				<form action="" method="POST">
				 	<div id="first_game">
			 			<p>1ère partie</p>
					 	<input type="submit" name="stand_1" value="Rester"/>
					 	<input type="submit" name="hit_1" value="Tirer une carte"/>
					 	<?php
							if ($_SESSION['first_Card'] == $_SESSION['second_Card'] && $_SESSION['FirstChoice_1'] == true) {
								echo '<input type="submit" name="double_1" value="Doubler" />';
							}
					 	?>
				 	</div>
				 	<div id="second_game">
				 		<p>2ème partie</p>
						<input type="submit" name="stand_2" value="Rester" disabled="disabled" />
					 	<input type="submit" name="hit_2" value="Tirer une carte" disabled="disabled"/>
				 	</div>
				</form>
			<?php }  else { ?>
				<form action="" method="POST">
				 	<div id="first_game">
				 		<p>1ère partie</p>
					 	<input type="submit" name="stand_1" value="Rester"/>
					 	<input type="submit" name="hit_1" value="Tirer une carte"/>
					 	<?php
							if ($_SESSION['first_Card'] == $_SESSION['second_Card'] && $_SESSION['FirstChoice_1'] == true) {
								echo '<input type="submit" name="double_1" value="Doubler" />';
							}
					 	?>
				 	</div>
				 	<div id="second_game">
				 		<p>2ème partie</p>
					 	<input type="submit" name="stand_2" value="Rester"/>
					 	<input type="submit" name="hit_2" value="Tirer une carte"/>
					 	<?php
							if ($_SESSION['first_Card'] == $_SESSION['second_Card'] && $_SESSION['FirstChoice_2'] == true) {
								echo '<input type="submit" name="double_2" value="Doubler" />';
							}
					 	?>
				 	</div>
				</form>
			<?php }
		} else {
			?>
			 <form action="" method="POST">
			 	<input type="submit" name="stand" value="Rester"/>
			 	<input type="submit" name="hit" value="Tirer une carte"/>
			 	<?php
					if ($_SESSION['FirstChoice'] == true) {
						echo '<input type="submit" name="double" value="Doubler" />';
					}
			 	?>
			 	<?php
					if ($_SESSION['first_Card'] == $_SESSION['second_Card'] && $_SESSION['split'] == false && $_SESSION['FirstChoice'] == true) {
						echo '<input type="submit" name="split" value="Split"/>';
					}
			 	?>
			 </form>
		<?php }	?>

		

<?php require('footer.php'); ?>