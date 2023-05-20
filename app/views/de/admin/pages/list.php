<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo Routes::getUri('admin_dashboard');?>">Administration</a></li>
    <li class="breadcrumb-item active" aria-current="page">Seiten</li>
  </ol>
</nav>

<h1>Seiten</h1>

<?php echo $this->renderMessage('info','Selbst erstellte Seiten haben <b>keine Route</b>! Weitere Infos im <a target="_blank" href="https://github.com/nekocari/miniTCG/wiki/Add-a-new-page">Wiki auf GitHub</a>.'); ?>

<div class="table-responsive">

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Sprache</th>
                <th>Dateiname</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach($pages as $page){ ?>
            <tr>
                <td><?php echo SUPPORTED_LANGUAGES[$page['language']]; ?></td>
                <td><?php echo $page['filename']; ?></td>
                <td class="text-right">
                    <form class="d-inline" method="POST" action="<?php echo ROUTES::getUri('admin_pages_edit'); ?>">
                        <button class="btn btn-sm btn-primary" name="file_path" value="<?php echo $page['path']; ?>" type="submit">
                            <i class="fas fa-pencil-alt"></i> <span class="d-none d-md-inline">bearbeiten</span>
                        </button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>