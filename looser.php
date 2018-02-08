<?php require('head.php'); ?>
<div id="looser">
	<h2>Vous n'avez plus d'argent ! Pour continuer de jouer, merci d'acheter plus de BitCoins.</h2>
	<div class="grid align__item">
    	<div class="card">

      <div class="card__header">
        <h3 class="card__title">Payment Details</h3>
        <svg xmlns="http://www.w3.org/2000/svg" class="card__logo" width="140" height="43" viewBox="0 0 175.7 53.9"><style>.visa{fill:#fff;}</style><path class="visa" d="M61.9 53.1l8.9-52.2h14.2l-8.9 52.2zm65.8-50.9c-2.8-1.1-7.2-2.2-12.7-2.2-14.1 0-24 7.1-24 17.2-.1 7.5 7.1 11.7 12.5 14.2 5.5 2.6 7.4 4.2 7.4 6.5 0 3.5-4.4 5.1-8.5 5.1-5.7 0-8.7-.8-13.4-2.7l-2-.9-2 11.7c3.3 1.5 9.5 2.7 15.9 2.8 15 0 24.7-7 24.8-17.8.1-5.9-3.7-10.5-11.9-14.2-5-2.4-8-4-8-6.5 0-2.2 2.6-4.5 8.1-4.5 4.7-.1 8 .9 10.6 2l1.3.6 1.9-11.3M164.2 1h-11c-3.4 0-6 .9-7.5 4.3l-21.1 47.8h14.9s2.4-6.4 3-7.8h18.2c.4 1.8 1.7 7.8 1.7 7.8h13.2l-11.4-52.1m-17.5 33.6c1.2-3 5.7-14.6 5.7-14.6-.1.1 1.2-3 1.9-5l1 4.5s2.7 12.5 3.3 15.1h-11.9zm-96.7-33.7l-14 35.6-1.5-7.2c-2.5-8.3-10.6-17.4-19.6-21.9l12.7 45.7h15.1l22.4-52.2h-15.1"/><path fill="#F7A600" d="M23.1.9h-22.9l-.2 1.1c17.9 4.3 29.7 14.8 34.6 27.3l-5-24c-.9-3.3-3.4-4.3-6.5-4.4"/></svg>

      </div>

      <form action="" method="post" class="form">

        <div class="card__number form__field">
          <label for="card__number" class="card__number__label">Card Number</label>
          <input type="text" id="card__number" class="card__number__input" placeholder="4000 1234 5678 9010">
        </div>

        <div id="expiration_ccv">
	        <div class="card__expiration form__field">
	          <label for="card__expiration__year">Expiration</label>
	          <select name="" id="card__expiration__year">
	            <option value="january">January</option>
	            <option value="februrary">Februrary</option>
	            <option value="march">March</option>
	            <option value="april">April</option>
	            <option value="may">May</option>
	            <option value="june">June</option>
	            <option value="july">July</option>
	            <option value="august">August</option>
	            <option value="september">September</option>
	            <option value="october">October</option>
	            <option value="november">November</option>
	            <option value="december">December</option>
	          </select>

	          <select name="" id="">
	            <option value="2018">2018</option>
	            <option value="2019">2019</option>
	            <option value="2020">2020</option>
	            <option value="2021">2021</option>
	            <option value="2022">2022</option>
	          </select>
	      	</div>

	        <div class="card__ccv form__field">
	          <label for="">CCV</label>
	          <input type="number" class="card__ccv__input" placeholder="583" maxlength="3">
	        </div>
        </div>

      </form>

    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script>
  	(function($) {
  'use strict';

  // Selectmenu
  $('select').selectmenu();
  
}(jQuery));
  </script>
	<form action="Accueil.php" method="POST" id="refill">
		<input type="submit" name="low" value="100 BitCoins"/>
		<input type="submit" name="medium" value="500 BitCoins"/>
		<input type="submit" name="high" value="1000 BitCoins"/>
	</form>
</div>
<?php require('footer.php'); ?>