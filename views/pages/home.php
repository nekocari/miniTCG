<div class="jumbotron">
	<h1 class="display-4">miniTCG</h1>
	<p>Eine kleine Trading Card Game Applikation in PHP</p>
</div>

<?php 
require_once PATH.'models/news.php'; 
News::display(3); 
?>