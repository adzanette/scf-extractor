<?php
$template->setTitle('frame.list');
$pagination = $template->paginate($page, $count, 'frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Frames do verbo '<?php echo $verb->verb; ?>'</h1>
      <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage)); ?>">Voltar para os verbos</a>
      <?php echo $pagination; ?>
    </div>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th>Frame</th>
        <th>Forma</th>
        <th>Frequ&ecirc;ncia</th>
        <th>Exemplos</th>
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
          <a class="btn btn-success" title="Ver Exemplos" href="<?php echo $template->getLink('example-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage, 'frameId' => $frame->id_frame, 'framePage' => $page, 'page' => 1))?>">
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
    <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('verb-list', array('corpus' => $corpus, 'page' => $verbPage))?>">Voltar para os verbos</a>
    </div>
  </div>
</div>
?>
