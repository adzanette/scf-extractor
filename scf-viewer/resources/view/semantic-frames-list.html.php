<?php
$template->setTitle('semantic.frame.list');
$pagination = $template->paginate($page, $count, 'semantic-frames-list', array('corpus' => $corpus));
?>
<div class="row">
  <div class="col-md-12 well text-center">
    <h1><?php echo $template->translate('semantic.frame.list')?></h1>
    <?php echo $pagination;?>
  </div>
</div>
<div class="row page-list">
  <div class="col-md-10 col-md-offset-1 text-center">
  <table class="table table-bordered table-hover">
  <thead>
    <tr class="header">
      <th class="text-center"><?php echo $template->translate('frame.table.frame')?></th>
      <th class="text-center"><?php echo $template->translate('frame.table.frequency')?></th>
      <th class="text-center"><?php echo $template->translate('frame.table.verbs')?></th>
    </tr>
  </thead>
  <?php
  foreach($frames as $frame){
    ?>
    <tr>
      <td><?php echo htmlspecialchars($frame->frame); ?></td>
      <td><?php echo $frame->count; ?></td>
      <td>
        <a class="btn btn-info" title="Ver Verbos" href="<?php echo $template->getLink('semantic-frames-verbs', array('corpus' => $corpus, 'frame' => urlencode($frame->frame), 'framePage' => $page)); ?>">
          <span class="glyphicon glyphicon-play icon-white"></span>
        </a>
      </td>
    </tr>
  <?php
  }
  ?>
  </table>
  </div>
</div>
<div class="row">
  <div class="col-md-12 well text-center">
    <?php echo $pagination;?>
  </div>
</div>
