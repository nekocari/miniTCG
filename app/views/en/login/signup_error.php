<div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">Sign up failed</h4>
	<ul>
	<?php 
    foreach ($errors as $error){ 
        echo "<li>$error</li>";
    } 
    ?>
    </ul>
	<hr><a class="alert-link" href="javascript:history.go(-1);">back</a>
</div>