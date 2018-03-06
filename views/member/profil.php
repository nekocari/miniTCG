<table class="mb-4">
	<tr>
		<td>[LEVELCARD]</td>
    	<td>
    		<h1><?php echo $member->getName(); ?></h1>
    		<div>
        		Level: <?php echo $member->getLevel(); ?><br>
            	Karten: <?php echo $member->getCardCount(); ?><br>
            	Master: <?php echo $member->getMasterCount(); ?>
        	</div>
        </td>
	</tr>
</table>
<div class="float-left"></div>
<div>
	
</div>

<?php include $partial_uri ?>