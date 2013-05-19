<?php
$template->setTitle('frame.list');
$pagination = $template->paginate($page, $count, 'semantic-frames', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Frames do verbo '<?php echo $verb->verb; ?>'</h1>
      <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>">Voltar para os verbos</a>
      <?php echo $pagination;?>
    </div>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th>Frame</th>
        <th>Frequ&ecirc;ncia</th>
      </tr>
    </thead>
    <?php
    foreach($frames as $frame){
      ?>
      <tr>
        <td><?php echo htmlspecialchars($frame->frame, ENT_QUOTES); ?></td>
        <td><?php echo $frame->frequency; ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  <div class="row-fluid">
    <div class="span12 well text-center">
      <?php echo $pagination;?>
      <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>">Voltar para os verbos</a>
    </div>
  </div>
</div>
?>
