<?php
$template->setTitle('verb.list');
$pagination = $template->paginate($page, $count, 'verb-list', array('corpus' => $corpus));
?>
<div class="row">
  <div class="col-lg-12 well text-center">
    <h1><?php echo $template->translate('verb.list')?></h1>
    <a href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus), true, true); ?>" target="_blank" class="btn btn-info"><?php echo $template->translate('see.semantic.frames')?></a>
    <a class="btn btn-warning" href="<?php echo $template->getLink('index'); ?>"><?php echo $template->translate('change.corpus')?></a>
    <?php echo $pagination;?>
  </div>
</div>
<div class="row page-list">
  <div class="col-lg-10 col-lg-offset-1 text-center">
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="header">
          <th class="text-center"><?php echo $template->translate('verb.table.verb')?></th>
          <th class="text-center"><?php echo $template->translate('verb.table.frequency')?></th>
          <th class="text-center"><?php echo $template->translate('verb.table.frames')?></th>
          <th class="text-center"><?php echo $template->translate('verb.table.semantic.frames')?></th>
        </tr>
      </thead>
    <?php
    foreach($verbs as $verb){
      ?>
      <tr>
        <td><?php echo $verb->verb; ?></td>
        <td><?php echo $verb->frequency; ?></td>
        <td>
          <a class="btn btn-info" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $page, 'page' => 1))?>">
            <span class="glyphicon glyphicon-play icon-white"></span>
          </a>
        </td>
        <td>
          <a class="btn btn-info" href="<?php echo $template->getLink('semantic-frames', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $page, 'page' => 1))?>">
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
  <div class="col-lg-12 text-center">
    <?php echo $pagination;?>
    <a class="btn btn-warning" href="<?php echo $template->getLink('index'); ?>"><?php echo $template->translate('change.corpus')?></a>
  </div>
</div>
  

