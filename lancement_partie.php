<?php require('head.php'); ?>
<?php 

	$_SESSION['bet'] = $_POST['bet'];
	echo "<h2>Toutes les cartes</h2>";

	// Fill the deck with 52 cards * 6
	$sabot = $bdd->prepare("INSERT INTO sabot (Card_Name, Card_Value, Card_Number) VALUES (?,?,?)");
	$sabot->bindParam('1', $Card_Name);
	$sabot->bindParam('2', $Card_Value);
	$sabot->bindParam('3', $Card_Number);
	$s = 0;

	while ($s < 6) {
		// Select All 52 Cards Randomly
		$All_Cards = $bdd->prepare("SELECT * FROM cards ORDER BY RAND()");
		$All_Cards->execute();

		// Insert into Sabot
		while ($All_Card = $All_Cards->fetch()) {
			$Card_Name = $All_Card['Card_Name'];
			$Card_Value = $All_Card['Card_Value'];
			$Card_Number = $All_Card['Card_Number'];
			// echo $Card_Name . ' qui vaut : ' . $Card_Value . '<br/>';

			$sabot->execute();
		} 
		$s++;
	}

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

	$Player_Score = 0;
	$Dealer_Score = 0;

	for ($i=0; $i < 3; $i++) { 
		if ($i == 0) {
			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_Number = $sabot[0]['Card_Number'];
			$Card_ID = $sabot[0]['ID'];
			
			// Add card in the Game Table
			$Player_Name = 'Player';
			$game->execute();

			if ($Player_Score <= 21 && $Card_Value == 1) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1");
				$CheckAs->bindParam('1', $Card_Value);
				$Card_Value = 11;
				$CheckAs->execute();
			} elseif ($Card_Value == 1 && $Player_Score > 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11");
				$CheckAs->bindParam('1', $Card_Value);
				$Card_Value = 1;
				$CheckAs->execute();
			} else {
				echo "Ok";
			}

			 echo "<p>Le joueur tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();
		} elseif ($i == 1) {
			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_Number = $sabot[0]['Card_Number'];
			$Card_ID = $sabot[0]['ID'];

			// Add card in the Game Table
			$Player_Name = 'Dealer';
			$game->execute();

			if ($Dealer_Score <= 21 && $Card_Value == 1) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1");
				$CheckAs->bindParam('1', $Card_Value);
				$Card_Value = 11;
				$CheckAs->execute();
				echo "Dealer Check AS";
			} elseif ($Card_Value == 1 && $Dealer_Score > 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11");
				$CheckAs->bindParam('1', $Card_Value);
				$Card_Value = 1;
				$CheckAs->execute();
			} else {
				echo "Ok";
			}

			echo "<p>Le dealer tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();
		} elseif ($i == 2) {
			$query->execute();
			$sabot = $query->fetchAll(PDO::FETCH_ASSOC);

			$Card_Name = $sabot[0]['Card_Name'];
			$Card_Value = $sabot[0]['Card_Value'];
			$Card_Number = $sabot[0]['Card_Number'];
			$Card_ID = $sabot[0]['ID'];

			// Add card in the Game Table
			$Player_Name = 'Player';
			$game->execute();

			if (($Player_Score <= 21 && $Card_Value == 1) && ($Player_Score + 11) <= 21) {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 1 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 11;
				$CheckAs->execute();
			} else {
				$CheckAs = $bdd->prepare("UPDATE game SET Card_Value = ? WHERE Card_Value = 11 AND Card_ID = ?");
				$CheckAs->bindParam('1', $Card_Value);
				$CheckAs->bindParam('2', $Card_ID);
				$Card_Value = 1;
				$CheckAs->execute();
			}

			echo "<p>Le joueur tire la carte : " . $Card_Name . " qui vaut : " . $Card_Value . "</p>";

			// Remove card after draw
			$deleteCard->execute();
		}else {
			echo "Error";
		}

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

	}

	header('Location: blackjack.php');
	 ?>