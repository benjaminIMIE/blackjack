<?php require('head.php'); ?>
<?php 
	session_destroy();
	header('location: login.php');