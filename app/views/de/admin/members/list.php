<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Verwaltung</a></li>
    <li class="breadcrumb-item active" aria-current="page">Mitglieder</li>
  </ol>
</nav>

<h1>Mitgliederliste</h1>



<form method="GET" action="">
	<div class="row">
		<div class="col-12 col-md">
			<div class="input-group input-group-sm">
				<select class="form-select" name="order">
					<option value="id" <?php if(isset($_GET['order']) AND $_GET['order']=='id'){ echo 'selected'; } ?>>ID</option>
					<option value="name" <?php if(isset($_GET['order']) AND $_GET['order']=='name'){ echo 'selected'; } ?>>Name</option>
				</select>
				<select class="form-select" name="direction">
					<option value="ASC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='ASC'){ echo 'selected'; } ?>>aufsteigend</option>
					<option value="DESC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='DESC'){ echo 'selected'; } ?>>absteigend</option>
				</select>
				<button class="btn btn-dark"><i class="fas fa-exchange-alt fa-rotate-90"></i></button>
			</div>
		</div>
		<div class="col-12 col-md py-md-0 py-1">
			<div class="input-group input-group-sm">
				<input class="form-control" type="text" name="search" list="member-list-data" value="<?php echo $list->getSearchStr(); ?>">
				<datalist id="member-list-data">
					<?php foreach(Member::getAll() as $member){ ?>
						<option><?php echo $member->getName(); ?></option>
					<?php } ?>
				</datalist>
				<button class="btn btn-dark"><i class="fas fa-search "></i></button>
			</div>
		</div>
	</div>
</form>


<?php if($list->getCount() > 0){ ?>

<table class="table table-striped">
	<thead>
    	<tr>
    		<th class="d-none d-sm-block">ID</th>
    		<th>Name</th>
    		<th class="d-none d-md-block">Mail</th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($list->getItems() as $member){ ?>
    	<tr style="white-space:nowrap">
    		<td class="d-none d-sm-block"><?php echo $member->getId(); ?></td>
    		<td><a href="<?php echo $member->getProfilLink(); ?>"><?php echo $member->getName(); ?></a></td>
    		<td class="d-none d-md-block"><?php echo $member->getMail(); ?></td>
    		<td class="text-right">
    			<div class="dropdown">
					<a class="dropdown-toggle" href="#"  id="memberEditDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aktionen</a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="memberEditDropdown">
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_edit');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-pencil-alt"></i> Daten bearbeiten</a>  
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_card');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-portrait"></i> Membercard bearbeiten</a>  
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_gift_cards');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-plus"></i> Karten geben</a>   
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_gift_money');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-hand-holding-usd"></i> <?php echo $currency_name; ?> geben</a>     
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_manage_rights');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-unlock-alt"></i> Rechte verwalten</a>         
            			<a class="dropdown-item" href="<?php echo Routes::getUri('admin_member_reset_password');?>?id=<?php echo $member->getId(); ?>">
            				<i class="fas fa-key"></i> Passwort zurücksetzen</a>
                    </div>
				</div>   			
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>

<?php }else{ echo $this->renderMessage('info','Keine Einträge'); } ?>

<?php echo $list->getPagination()->getPaginationHTML(); ?>