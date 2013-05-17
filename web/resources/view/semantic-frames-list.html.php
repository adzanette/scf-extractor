<?php
$template->setTitle('verb.list');
$pagination = $template->paginate($page, $count, 'semantic-frames-list', array('corpus' => $corpus));
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Frames Sem&acirc;nticos</h1>
      <?php echo $pagination;?>
    </div>
  </div>
  <table class="table table-bordered table-hover">
  <thead>
    <tr class="header">
      <th>Frame</th>
      <th>Frequ&ecirc;ncia</th>
      <th>Ver Verbos</th>
    </tr>
  </thead>
  <?php
  foreach($frames as $frame){
    ?>
    <tr>
      <td><?php echo htmlspecialchars($frame->frame); ?></td>
      <td><?php echo $frame->count; ?></td>
      <td>
        <a class="btn btn-success" title="Ver Verbos" href="<?php echo $template->getLink('semantic-frames-verbs', array('corpus' => $corpus, 'frame' => rawurlencode($frame->frame), 'framePage' => $page)); ?>">
          <span class="icon-play icon-white">
        </a>
      </td>
    </tr>
  <?php
  }
  ?>
  </table>
  <div class="row-fluid">
    <div class="span12 well text-center">
      <?php echo $pagination;?>
    </div>
  </div>
</div>