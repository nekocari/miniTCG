<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
	<?php foreach($this->getBreadcrumbs() as $key => $bc) { ?>
    	<?php if($key+1 == 1){ ?>
    		<li class="breadcrumb-item"><a href="<?php echo $bc->getUri(); ?>" class="icon icon-folder"> <?php echo $bc->getName(); ?></a></li>
		<?php }elseif($key+1 < count($this->getBreadcrumbs())){ ?>
    		<li class="breadcrumb-item"><a href="<?php echo $bc->getUri(); ?>"><?php echo $bc->getName(); ?></a></li>
		<?php }else{ ?>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $bc->getName(); ?></li>
		<?php } ?>
	<?php } ?>
  </ol>
</nav>
