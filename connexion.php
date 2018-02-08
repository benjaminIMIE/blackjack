<?php 

	$user = 'root';
	$password = '';
	$db = 'blackjack';
	$host = 'localhost:3308';

	try {	

		$bdd = new PDO('mysql:host='.$host.';dbname='.$db, $user, $password);
		$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


	} catch (PDOException $e) {

		echo 'Connexion échouée : ' . $e->getMessage();
	}

 ?>


