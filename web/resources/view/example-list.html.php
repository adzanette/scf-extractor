<?php
$template->setTitle('frame.list');
$template->addJs('example.js');
$template->addTextJs('var corpus = "'.$corpus.'";');
$verb = $frame->verb;
$pagination = $template->paginate($page, $count, 'frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
  <script>
    var corpus = "<?php echo $corpus; ?>";
  </script>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Exemplos do frame '<?php echo $frame->frame; ?>' do verbo '<?php echo $verb->verb; ?>'</h1>
        <a class="btn btn-warning" title="Voltar para os frames" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'page' => $framePage, 'verbPage' => $verbPage, 'verbId' => $verb->id_verb)); ?>">Voltar para os frames</a>
        <?php echo $pagination; ?>
      </div>
    </div>
  <div class="accordion" id="examples">
  <?php
  $min = 1;
  $e = $min + 1;
  foreach($examples as $example){
    $exampleId = $example->id_example;
    $sentence = $example->sentence;
    $arguments = $example->arguments;
    ?>  
    <div class="accordion-group" id="<?php echo $exampleId; ?>">
      <div class="accordion-heading">
        <button type="button" class="btn btn-danger btn-mini delete-example" title="Excluir Exemplo">
          <i class="icon-remove icon-white"></i>
        </button>
        <span class="label label-info">Exemplo <?php echo $e++; ?></span>
        <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($sentence->raw_sentence); ?></h4>
        <div class="arguments" id="arguments-<?php echo $exampleId; ?>">
        <?php
        $i = 1;
        var_dump($arguments);
        die();
        foreach($arguments as $argument){
          ?>
          <div id="argument-<?php echo $argument->id_argument; ?>">
            <div class="row-fluid">
              <div class="span1 offset1"><?php echo "ARG_".$i; ?></div>
              <div class="span2"><?php echo $argument->argument; ?></div>
              <div class="span2">
                <input type="text" name="syntax_<?php echo $argument->id_argument; ?>" value="<?php echo $argument->sintax; ?>" />
              </div>
              <div class="span3">
                <?php //is_null($argument->semantic) ? select('role_'.$argument->id_argument, $roles, '') : select('role_'.$argument['id_argument'], $roles, $argument['semantic']); ?>
              </div>
              <div class="span3">
                <input type="button" title="Salvar Argumento" class="btn btn-info save-argument" id_argument="<?php echo $argument->id_argument; ?>" value="Salvar">
                <input type="button" title="Excluir Argumento" class="btn btn-danger delete-argument" id_argument="<?php echo $argument->id_argument; ?>" value="Excluir">
              </div>
            </div>
          </div>
          <?php
          $i++;
        }
        ?>
      </div>
      <div id="example<?php echo $exampleId; ?>" class="accordion-body collapse in">
        <div class="accordion-inner">     
            <span class="label label-info">Anota&ccedil;&atilde;o</span>
            <pre><?php echo $sentence->parsed_sentence; ?></pre>
          </div>
        </div>
      </div>
    </div>
  <?php      
   
    }
  ?>
  <div class="row-fluid">
      <div class="span12 well text-center">
        <?php echo $pagination;?>
        <a class="btn btn-warning" title="Voltar para os frames" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'page' => $framePage, 'verbPage' => $verbPage, 'verbId' => $verb->verb_id)); ?>">Voltar para os frames</a>
      </div>
    </div>
  </div>  
</div>

