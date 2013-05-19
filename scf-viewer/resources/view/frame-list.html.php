<?php
$template->setTitle('frame.list');
$pagination = $template->paginate($page, $count, 'frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1><?php echo $template->translate('frame.list.verb', array('verb' => $verb->verb))?></h1>
      <a class="btn btn-warning" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>"><?php echo $template->translate('verb.list.back')?></a>
      <?php echo $pagination; ?>
    </div>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th><?php echo $template->translate('frame.table.frame')?></th>
        <th><?php echo $template->translate('frame.table.type')?></th>
        <th><?php echo $template->translate('frame.table.frequency')?></th>
        <th><?php echo $template->translate('frame.table.examples')?></th>
      </tr>
    </thead>
    <?php
    foreach($frames as $frame){
      ?>
      <tr>
        <td><?php echo $frame->frame; ?></td>
        <td><?php 
            if ($frame->is_passive == 1){
              ?><span class="label label-info">PASSIVA</span><?php 
            }else{
              ?><span class="label label-important">ATIVA</span><?php
            } 
            ?>
        </td>
        <td><?php echo $frame->frequency; ?></td>
        <td>
          <a class="btn btn-success" href="<?php echo $template->getLink('example-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage, 'frameId' => $frame->id_frame, 'framePage' => $page, 'page' => 1))?>">
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
      <?php echo $pagination; ?>
      <a class="btn btn-warning" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>"><?php echo $template->translate('verb.list.back')?></a>
    </div>
  </div>
</div>
?>
