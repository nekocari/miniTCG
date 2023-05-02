<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Spiele</li>
  </ol>
</nav>

<h1>Spiele</h1>

<form method="POST" action="">
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
		    	<tr>
		    		<th>Name</th>
		    		<th class="text-center">Art</th>
		    		<th class="text-center">Wartezeit</th>
		    		<th></th>
		    	</tr>
			</thead>
			<tbody>
		<?php foreach($game_settings as $game){ ?>
		    	<tr style="white-space:nowrap">
		    		<td><?php echo $game->getName('de'); ?></td>
		    		<td class="text-center"><?php echo $game->getType(); ?></td>
		    		<td class="text-center"><?php if(!$game->isDailyGame()){ echo $game->getWaitTime().' min'; }else{ echo '-'; } ?></td>
		    		<td class="text-right">
		    			<a href="<?php echo Routes::getUri('admin_games_edit');?>?id=<?php echo $game->getId(); ?>" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i> bearbeiten</a>
		    			<button class="btn btn-sm btn-danger" name="delete_game" value="<?php echo $game->getId(); ?>" 
		    			onclick="return confirm('Spiel <?php echo $game->getName('de'); ?> wirklich löschen?')"><i class="fas fa-times"></i> löschen</button>
		    		</td>
		    	</tr>
		<?php } ?>
			</tbody>
		</table>
	</div>
</form>
<p class="text-center"><a class="btn btn-primary" href="<?php echo Routes::getUri('admin_games_add');?>">neues Spiel anlegen</a></p>