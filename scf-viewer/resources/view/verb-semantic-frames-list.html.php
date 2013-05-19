<?php
$template->setTitle('verb.list');
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1><?php echo $template->translate('semantic.frames.verb', array('frame' => htmlentities($frame)));?></h1>
      <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>"><?php echo $template->translate('frame.list.back'); ?></a>
    </div>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th><?php echo $template->translate('verb.table.verb'); ?></th>
        <th><?php echo $template->translate('verb.table.frequency'); ?></th>
        <th><?php echo $template->translate('frame.table.examples'); ?></th>
      </tr>
    </thead>
    <?php
    foreach($verbs as $verb){
      ?>
      <tr>
        <td><?php echo $verb->verb; ?></td>
        <td><?php echo $verb->frequency; ?></td>
        <td>
          <a class="btn btn-success" href="<?php echo $template->getLink('semantic-frame-examples', array('corpus' => $corpus, 'frame' => urlencode($frame), 'verbId' => $verb->id_verb, 'framePage' => $framePage)); ?>">
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
      <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>"><?php echo $template->translate('frame.list.back'); ?></a>
    </div>
  </div>
</div>