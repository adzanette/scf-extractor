<?php
$template->setTitle('semantic.frame.list');
$pagination = $template->paginate($page, $count, 'semantic-frames', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
<div class="row">
  <div class="col-md-12 well text-center">
    <h1><?php echo $template->translate('semantic.frame.list'); ?></h1>
    <h3><?php echo $template->translate('verb.subtitle', array('verb' => $verb->verb))?></h3>
    <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>"><?php echo $template->translate('verb.list.back'); ?></a>
    <?php echo $pagination;?>
  </div>
</div>
<div class="row page-list">
  <div class="col-md-10 col-md-offset-1 text-center">
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th class="text-center"><?php echo $template->translate('frame.table.frame'); ?></th>
        <th class="text-center"><?php echo $template->translate('frame.table.frequency'); ?></th>
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
  </div>
</div>
<div class="row">
  <div class="col-md-12 well text-center">
    <?php echo $pagination;?>
    <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>"><?php echo $template->translate('verb.list.back'); ?></a>
  </div>
</div>
