<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_member_index');?>">Members</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo $currency_name; ?></li>
  </ol>
</nav>

<h1>Hand out <?php echo $currency_name; ?></h1>

<h4>Member: <b><?php echo $member->getName(); ?></b></h4>

<form method="POST" action="">
    <div class="mx-2 mb-2">How much?</div>
    <div class="mx-2 mb-2"><input type="number" name="addMoney" class="form-control" required></div>
    <div class="mx-2 mb-2"><textarea name="text" class="form-control" placeholder="Reason?" required></textarea></div>
    <div class="mx-2 mb-2"><button class="btn btn-primary" name="add" value="1">submit</button></div>
</form>