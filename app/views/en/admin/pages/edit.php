<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_pages');?>">Pages</a></li>
    <li class="breadcrumb-item active" aria-current="page">edit</li>
  </ol>
</nav>

<h1>Edit Page</h1>

<form method="POST">
  <table class="table table-striped">
    <tbody>
        <tr>
          <td style="vertical-align:middle">File:</td>
          <td>
            <input class="form-control" type="text" name="" value="<?php echo $file['path']; ?>" disabled>
        </td>
        </tr>
        <tr>
          <td colspan="2">
            <textarea class="form-control" name="content" style="max-height: 50vh; font-family: monospace;" rows="15"><?php echo $file['content']; ?></textarea>
          </td>
        </tr>
    </tbody>
  </table>
    
  <p class="text-center my-2">
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_pages');?>">go back</a> 
        &bull; <input class="btn btn-primary" type="submit" name="submit" value="save">
    <input type="hidden" name="file_path" value="<?php echo $file['path']; ?>">
  </p>
</form>