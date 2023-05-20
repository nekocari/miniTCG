<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_pages');?>">Seiten</a></li>
    <li class="breadcrumb-item active" aria-current="page">bearbeiten</li>
  </ol>
</nav>

<h1>Seite bearbeiten</h1>

<form method="POST">
  <table class="table table-striped">
    <tbody>
        <tr>
          <td style="vertical-align:middle">Datei:</td>
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
		<a class="btn btn-dark" href="<?php echo Routes::getUri('admin_pages');?>">zurück</a> 
        &bull; <input class="btn btn-primary" type="submit" name="submit" value="speichern">
    <input type="hidden" name="file_path" value="<?php echo $file['path']; ?>">
  </p>
</form>