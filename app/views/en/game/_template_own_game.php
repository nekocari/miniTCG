<!-- display name as set in  "Administration > Games"  -->
<h1><?php echo $game->getName('en'); // 'en' for english name (see "config/constants.php" constant SUPPORTED_LANGUAGES) ?></h1>
<!---------------------------------------------------------------------------------->
<h2>lass uns spielen!</h2>

<!-- display description as set in  "Administration > Games"  -->
<p class="text-center"><?php echo $game->getDescription('en'); // 'en' for english text ?></p>
<!---------------------------------------------------------------------------------->


<!-- v add necessary HTML code for game v -->







<!-- leave in "form" tag if your following the Wiki! ------------------------------->
<form id="my-custom-game" class="text-center" method="POST">
	<input id="result-input" type="hidden" name="game_result" value="">
</form>
<!---------------------------------------------------------------------------------->

<p class="text-center">
	<a class="btn btn-dark" href="javascript:history.go(-1)">zurück zur Übersicht</a>
</p>