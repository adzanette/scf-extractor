<?php
$template->setTitle('verb.list');
?>
<div class="row">
  <div class="col-lg-12 well text-center">
    <h1><?php echo $template->translate('semantic.frames.verb', array('frame' => htmlentities($frame)));?></h1>
    <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>"><?php echo $template->translate('frame.list.back'); ?></a>
  </div>
</div>
<div class="row page-list">
  <div class="col-lg-10 col-lg-offset-1 text-center">
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th class="text-center"><?php echo $template->translate('verb.table.verb'); ?></th>
        <th class="text-center"><?php echo $template->translate('verb.table.frequency'); ?></th>
        <th class="text-center"><?php echo $template->translate('frame.table.examples'); ?></th>
      </tr>
    </thead>
    <?php
    foreach($verbs as $verb){
      ?>
      <tr>
        <td><?php echo $verb->verb; ?></td>
        <td><?php echo $verb->frequency; ?></td>
        <td>
          <a class="btn btn-info" href="<?php echo $template->getLink('semantic-frame-examples', array('corpus' => $corpus, 'frame' => urlencode($frame), 'verbId' => $verb->id_verb, 'framePage' => $framePage)); ?>">
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
  <div class="col-lg-12 well text-center">
    <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>"><?php echo $template->translate('frame.list.back'); ?></a>
  </div>
</div>
