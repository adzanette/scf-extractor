<?php
$template->setTitle('verb.list');
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Exemplos do frame sem&acirc;ntico '<?php echo $frame; ?>' do verbo '<?php echo $verb->verb; ?>'</h1>
      <a class="btn btn-warning" title="Voltar para os frames" href="semanticFramesVerbs.php?corpus=<?php echo $corpus; ?>&frame_page=<?php echo $frame_page; ?>&frame=<?php echo $frame; ?>">Voltar para os verbos</a>
    </div>
  </div>
  <div class="accordion" id="examples">
    <?php
    $e = 1;
    foreach ($sentences as $sentence){
      $id = $sentence->id_sentence;
      ?>  
      <div class="accordion-group" id="<?php echo $id; ?>">
        <div class="accordion-heading">
          <span class="label label-info">Exemplo <?php echo $e++; ?></span>
          <h4>&nbsp;&nbsp;&nbsp;<?php echo $sentence->raw_sentence; ?></h4>
        </div>
        <div id="example<?php echo $id; ?>" class="accordion-body collapse in">
          <div class="accordion-inner">     
              <span class="label label-info">Anota&ccedil;&atilde;o</span>
              <?php echo '<pre>'.$sentence->parsed_sentence.'</pre>'; ?>
          </div>
        </div>
      </div>
    <?php      
      }
    ?>
  </div>
  <div class="row-fluid">
    <div class="span12 well text-center">
      <a class="btn btn-warning" title="Voltar para os frames" href="semanticFramesVerbs.php?corpus=<?php echo $corpus; ?>&frame_page=<?php echo $frame_page; ?>&frame=<?php echo $frame; ?>">Voltar para os verbos</a>
    </div>
  </div>
</div>  
