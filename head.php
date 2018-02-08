<?php require('connexion.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>BlackJack Full PHP</title>
		<link rel="stylesheet" type="text/css" href="main.css">
	</head>
	<body>
		<section>
			<header>
				<div>
					<a href="accueil.php" id="home">
						<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 27.02 27.02" style="enable-background:new 0 0 27.02 27.02;" xml:space="preserve">
							<path d="M3.674,24.876c0,0-0.024,0.604,0.566,0.604c0.734,0,6.811-0.008,6.811-0.008l0.01-5.581
								c0,0-0.096-0.92,0.797-0.92h2.826c1.056,0,0.991,0.92,0.991,0.92l-0.012,5.563c0,0,5.762,0,6.667,0
								c0.749,0,0.715-0.752,0.715-0.752V14.413l-9.396-8.358l-9.975,8.358C3.674,14.413,3.674,24.876,3.674,24.876z"/>
							<path d="M0,13.635c0,0,0.847,1.561,2.694,0l11.038-9.338l10.349,9.28c2.138,1.542,2.939,0,2.939,0
								L13.732,1.54L0,13.635z"/>
							<polygon points="23.83,4.275 21.168,4.275 21.179,7.503 23.83,9.752 	"/>
						</svg>
					</a>
					<a href="highscores.php" id="highscore">Highscores</a>
				</div>
				<?php 
					if(isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
						echo '<div id="player_infos"><a href="profil.php" id="profil">';
						echo $_SESSION['pseudo'];
						echo '</a>';
						echo '<a href="logoff.php" id="logoff">Se déconnecter</a>';
						echo '<div id="cash">';
						$DisplayAccounts = $bdd->prepare("SELECT account FROM players WHERE Player_Name = ? AND MDP = ?");
						$DisplayAccounts->bindParam('1', $_SESSION['pseudo']);
						$DisplayAccounts->bindParam('2', $_SESSION['mdp']);
						$DisplayAccounts->execute(); 

						while ($DisplayAccount = $DisplayAccounts->fetch()) {
							$_SESSION['current_account'] = $DisplayAccount[0];
							echo $DisplayAccount[0] . ' 
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 273.6 360" enable-background="new 0 0 273.6 360" xml:space="preserve">
						<path d="M217.021,167.042c18.631-9.483,30.288-26.184,27.565-54.007c-3.667-38.023-36.526-50.773-78.006-54.404l-0.008-52.741
							h-32.139l-0.009,51.354c-8.456,0-17.076,0.166-25.657,0.338L108.76,5.897l-32.11-0.003l-0.006,52.728
							c-6.959,0.142-13.793,0.277-20.466,0.277v-0.156l-44.33-0.018l0.006,34.282c0,0,23.734-0.446,23.343-0.013
							c13.013,0.009,17.262,7.559,18.484,14.076l0.01,60.083v84.397c-0.573,4.09-2.984,10.625-12.083,10.637
							c0.414,0.364-23.379-0.004-23.379-0.004l-6.375,38.335h41.817c7.792,0.009,15.448,0.13,22.959,0.19l0.028,53.338l32.102,0.009
							l-0.009-52.779c8.832,0.18,17.357,0.258,25.684,0.247l-0.009,52.532h32.138l0.018-53.249c54.022-3.1,91.842-16.697,96.544-67.385
							C266.916,192.612,247.692,174.396,217.021,167.042z M109.535,95.321c18.126,0,75.132-5.767,75.14,32.064
							c-0.008,36.269-56.996,32.032-75.14,32.032V95.321z M109.521,262.447l0.014-70.672c21.778-0.006,90.085-6.261,90.094,35.32
							C199.638,266.971,131.313,262.431,109.521,262.447z"/>
					</svg>';
						}
						echo '</div></div>';
					}
				?>
			</header>