<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('member_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page">Membercard</li>
  </ol>
</nav>

<h1>Membercard</h1>

<h4>Member: <b><?php echo $member->getName(); ?></b></h4>


<form enctype="multipart/form-data" method="POST" action="" name="membercard" class="m-4">
	
	<div class="row">
		<div class="col-12 col-md text-center">
			<img src="<?php echo $member->getMemberCardUrl().'?t='.time(); ?>">
		</div>
		<div class="col-12 col-md">
			<p>Upload or replace Membercard.</p>
			<div class="input-group">
		    	<input type="file" name="file" class="form-control" required>
		    	<div class="input-group-append">
				<?php if(!$member->hasMemberCard()) { ?>
					<button class="btn btn-primary" type="submit" name="action" value="upload">upload</button>
				<?php }else{ ?>
					<button class="btn btn-primary" type="submit" name="action" value="replace">replace</button>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="id" value="<?php echo intval($_GET['id']); ?>">
</form>



<p class="text-center my-2">
	<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_member_index');?>">go back</a> 
</p>