<!-- Verwendet den Namen wie in der Administration unter "Verwaltung > Spiele" festgelegt -->
<h1><?php echo $game->getName('de'); // 'de' um den Namen auf Deutsch anzuzeigen (siehe "config/constants.php" Konstante SUPPORTED_LANGUAGES) ?></h1>
<!---------------------------------------------------------------------------------->
<h2>lass uns spielen!</h2>

<!-- Verwendet die Beschreibung wie in der Administration unter "Verwaltung > Spiele" festgelegt -->
<p class="text-center"><?php echo $game->getDescription('de'); // 'de' um den Beschreibungstext auf Deutsch anzuzeigen ?></p>
<!---------------------------------------------------------------------------------->


<!-- v Benötigter HTML Code für das Spiel oder persönliche Ergänzungen hier v -->







<!-- Lass diesen "form" Code stehen wenn du nach der Anleitung im Wiki arbeitest! -->
<form id="my-custom-game" class="text-center" method="POST">
	<input id="result-input" type="text" name="game_result" value="">
	<button>submit</button>
</form>
<!---------------------------------------------------------------------------------->

<p class="text-center">
	<a class="btn btn-dark" href="javascript:history.go(-1)">zurück zur Übersicht</a>
</p>