<?php require('head.php'); ?>
<div id="page_profil">
	<form action="" method="POST">
		<div>
			<label for="prenom">Votre prénom</label>
			<input type="text" id="prenom" name="prenom" value="<?php if(isset($_SESSION['pseudo'])){echo $_SESSION['pseudo'];} ?>" />
		</div>
		<div>
			<label for="mdp">Votre mot de passe</label>
			<input type="password" id="mdp" name="mdp" value="<?php if(isset($_SESSION['mdp'])){echo $_SESSION['mdp'];} ?>" />
		</div>
		<input type="submit" name="maj_info" value="Mettre à jour"/>
	</form>
	<?php
		//Update Player's profil
		if (isset($_POST['maj_info'])) {
			$update_Profil = $bdd->prepare('UPDATE players SET Player_Name = ?, MDP = ? WHERE Player_Name = "' . $_SESSION['pseudo'] . '" AND MDP = "' . $_SESSION['mdp'] . '" ');
			$update_Profil->bindParam('1',$_POST['prenom']);
			$update_Profil->bindParam('2',$_POST['mdp']);
			$update_Profil->execute();
		}
		echo $_SESSION['starting_Account'];
	 ?>

   <!-- Histogram -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<div id="container" style="width:50%; height:400px;"></div>

	<script>
    
		$(function () { 
  $('#container').highcharts({
        chart: {
            type: 'column',
            backgroundColor: 'black'
        },
        title: {
            text: 'Progression de la session de jeu',
            style: {  
              color: '#fff'
            }
        },
        xAxis: {
            tickWidth: 0,
            labels: {
              style: {
                  color: 'rgb(154,63,81)',
                 }
              },
            categories: ['Début de la partie', 'Maintenant']
        },
        yAxis: {
           gridLineWidth: .5,
		      gridLineDashStyle: 'dash',
		      gridLineColor: 'black',
           title: {
                text: '',
                style: {
                  color: '#333'
                 }
            },
            labels: {
              formatter: function() {
                        return Highcharts.numberFormat(this.value, 0, '', ',');
                    },
              style: {
                  color: 'rgb(154,63,81)',
                 }
              }
            },
        legend: {
            enabled: false,
        },
        credits: {
            enabled: false
        },
        tooltip: {
           valueSuffix: ' BitCoins'
        },
        plotOptions: {
		      column: {
			      borderRadius: 0,
             pointPadding: -0.12,
			      groupPadding: 0.1
            } 
		    },
        series: [{
            name: 'Argent sur votre compte ',
            data: [<?php echo $_SESSION['starting_Account']; ?>, <?php echo $_SESSION['current_account']; ?>]
        }]
    });
});
	</script>

</div>

<?php require('footer.php'); ?>