<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">News</li>
  </ol>
</nav>

<h1>News Overview</h1>

<form method="GET" action="">
	<div class="row">
		<div class="col-12 col-md">
			<div class="input-group input-group-sm">
				<select class="form-control" name="order">
					<option value="id" <?php if(isset($_GET['order']) AND $_GET['order']=='id'){ echo 'selected'; } ?>>ID</option>
					<option value="utc" <?php if(isset($_GET['order']) AND $_GET['order']=='utc'){ echo 'selected'; } ?>>Datum</option>
				</select>
				<select class="form-control" name="direction">
					<option value="ASC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='ASC'){ echo 'selected'; } ?>>ascending</option>
					<option value="DESC" <?php if(isset($_GET['direction']) AND $_GET['direction']=='DESC'){ echo 'selected'; } ?>>descending</option>
				</select><button class="btn btn-dark"><i class="fas fa-exchange-alt fa-rotate-90"></i></button>
			</div>
		</div>
		<div class="col-12 col-md py-md-0 py-1">
			<div class="input-group input-group-sm">
				<input class="form-control" type="text" name="search" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; } ?>">
				<button class="btn btn-dark"><i class="fas fa-search "></i></button>
			</div>
		</div>
	</div>
</form>

<div class="table-responsive">
<table class="table table-striped">
	<thead>
    	<tr>
    		<th>ID</th>
    		<th>Title<br>
 				<small>Date &bull; Author</small></th>
    		<th></th>
    	</tr>
	</thead>
	<tbody>
<?php foreach($list->getItems() as $entry){ ?>
    	<tr>
    		<td><?php echo $entry->getId(); ?></td>
    		<td><?php echo $entry->getTitle(); ?><br>
    			<small><b><?php echo $entry->getDate($this->login->getUser()->getTimezone()); ?></b> &bull; <?php echo $entry->getAuthor()->getName(); ?></small></td>
    		<td style="white-space:nowrap">
    			<form class="text-right" method="POST" action="">
    				<button class="btn btn-danger btn-sm del-link mx-1" onclick="return confirm('Are you sure you want to delete <?php echo $entry->getTitle(); ?>?');">
    					<i class="fas fa-times"></i> <span class="d-none d-md-inline">delete</span></button>
    				<input type="hidden" name="action" value="del_news">
    				<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">
    				
        			<a class="btn btn-sm btn-dark" href="<?php echo Routes::getUri('news_link_update');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-plus"></i> <span class="d-none d-md-inline">Update</span></a>
    				
        			<a class="btn btn-sm btn-primary" href="<?php echo Routes::getUri('news_edit');?>?id=<?php echo $entry->getId(); ?>">
        				<i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">edit</span></a>
				</form>
    		</td>
    	</tr>
<?php } ?>
	</tbody>
</table>
</div>

<?php echo $list->getPagination()->getPaginationHTML(); ?>