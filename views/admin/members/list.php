<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo ROUTES::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mitglieder</li>
  </ol>
</nav>

<h1>Mitgliederliste</h1>


<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Name</th>
    		<th>Mail</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($members as $member){ ?>
    	<tr>
    		<td><?php echo $member->getId(); ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
    		<td><?php echo $member->getMail(); ?></td>
    		<td class="text-right">
    			<div class="dropdown">
					<a class="dropdown-toggle" href="#"  id="memberEditDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aktionen</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="memberEditDropdown">
            			<a class="dropdown-item" href="<?php echo ROUTES::getUri('admin_member_edit');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-pencil-alt"></i> Daten bearbeiten</a>  
            			<a class="dropdown-item" href="<?php echo ROUTES::getUri('admin_member_gift_cards');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-plus"></i> Karten geben</a>   
            			<a class="dropdown-item" href="<?php echo ROUTES::getUri('admin_member_gift_money');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-hand-holding-usd"></i> <?php echo $currency_name; ?> geben</a>     
            			<a class="dropdown-item" href="<?php echo ROUTES::getUri('admin_member_manage_rights');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-unlock-alt"></i> Rechte verwalten</a>         
            			<a class="dropdown-item" href="<?php echo ROUTES::getUri('admin_member_reset_password');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-key"></i> Passwort zurücksetzen</a>
                    </div>
				</div>   			
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>

<?php echo $pagination; ?>