<h1>Tauschpartner finden</h1>
<h2>Suchergebnis</h2>

<?php if(count($trader)){ ?>
<table class="table">
	<colgroup>
		<col width="10%">
		<col width="60%">
		<col width="30%">
	</colgroup>
	<thead class="thead-light">
	<tr>
		<th>#</th>
		<th>Name</th>
		<th>Aktion</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($trader as $card_id => $member){ ?>
		<tr>
			<td><?php echo $member->getId(); ?></td>
			<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
			<td><a href="<?php echo Routes::getUri('trade');?>?card=<?php echo $card_id; ?>">Tauschangebot</a></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<?php }else{

    Layout::sysMessage('Leider bietete keiner diese Karte zum Tausch.');
    
} ?>