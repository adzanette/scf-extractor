<?php
$template->setTitle('verb.list');
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Verbos com o Frame Sem&acirc;ntico <?php echo $frame; ?></h1>
      <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>">Voltar para os frames</a>
    </div>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th>Verbo</th>
        <th>Frequ&ecirc;ncia</th>
        <th>Exemplos</th>
      </tr>
    </thead>
    <?php
    foreach($verbs as $verb){
      ?>
      <tr>
        <td><?php echo $verb->verb; ?></td>
        <td><?php echo $verb->frequency; ?></td>
        <td>
          <a class="btn btn-success"  title="Ver Exemplos" href="<?php echo $template->getLink('semantic-frame-examples', array('corpus' => $corpus, 'frame' => urlencode($frame), 'verbId' => $verb->id_verb, 'framePage' => $framePage)); ?>">
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
      <a class="btn btn-warning" title="Voltar para os verbos" href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus, 'page' => $framePage))?>">Voltar para os frames</a>
    </div>
  </div>
</div>