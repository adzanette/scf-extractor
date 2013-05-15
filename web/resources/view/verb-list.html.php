<?php
$template->setTitle('verb.list');
$pagination = $template->paginate($page, $count, 'verb-list', array('corpus' => $corpus));
?>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Listagem de verbos</h1>
        <a href="<?php echo $template->getLink('semantic-frames-list', array('corpus' => $corpus))?>" target="_blank" class="btn btn-inverse">Ver Lista de Frames Sem&acirc;nticos</a>
        <a class="btn btn-warning" title="Trocar de Corpus" href="<?php echo $template->getLink('index')?>">Trocar de corpus</a>
        <?php echo $pagination;?>
      </div>
    </div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="header">
          <th>Verbo</th>
          <th>Frequ&ecirc;ncia</th>
          <th>Frames</th>
          <th>Frames Sem&acirc;nticos</th>
        </tr>
      </thead>
    <?php
    foreach($verbs as $verb){
      ?>
      <tr>
        <td><?php echo $verb->verb; ?></td>
        <td><?php echo $verb->frequency; ?></td>
        <td>
          <a class="btn btn-success" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $page, 'page' => 1))?>">
            <span class="icon-play icon-white">
          </a>
        </td>
        <td>
          <a class="btn btn-success" href="<?php echo $template->getLink('semantic-frames', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $page, 'page' => 1))?>">
            <span class="icon-play icon-white">
          </a>
        </td>
      </tr>
    <?php
      $i++;
    }
    ?>
    </table>
    <div class="row-fluid">
      <div class="span12 well text-center">
        <?php echo $pagination;?>
        <a class="btn btn-warning" title="Trocar de Corpus" href="<?php echo $template->getLink('index')?>">Trocar de corpus</a>
      </div>
    </div>
  </div>
?>
