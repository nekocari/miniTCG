<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page">reset Password</li>
  </ol>
</nav>

<h1>Reset Password</h1>

<h4>Member: <b><?php echo $member->getName(); ?></b></h4>


<div class="alert alert-warning">
	<p>Here you can reset the members password. A new password will be sent to the email address they provided.</p>
	<p><b>This action cannot be undone!</b></p>
</div>

<form name="resetPassword" method="post" action="" onsubmit="return confirm('Are you sure?')">
	<div class="text-center">
		<button class="btn btn-danger" type="submit" name="reset">reset password for <?php echo $member->getName(); ?></button>
	</div>
</form>