<?php
$template->setTitle('frame.list');
$template->addJs('examples.js');
$template->addTextJs('var deleteExampleUrl = "'.$template->getLink('delete-example', array('corpus' => $corpus)).'";');
$template->addTextJs('var saveArgumentUrl = "'.$template->getLink('save-argument', array('corpus' => $corpus)).'";');
$template->addTextJs('var deleteArgumentUrl = "'.$template->getLink('delete-argument', array('corpus' => $corpus)).'";');

$verb = $frame->verb;
$pagination = $template->paginate($page, $count, 'example-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage, 'frameId' => $frame->id_frame, 'framePage' => $framePage));

?>
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
  $count = $offset + 1;
  foreach($examples as $example){
    $exampleId = $example->id_example;
    $sentence = $example->sentence;
    $arguments = $example->arguments(array('active = 1'));
    ?>  
    <div class="accordion-group" id="<?php echo $exampleId; ?>">
      <div class="accordion-heading">
        <button type="button" class="btn btn-danger btn-mini delete-example" title="Excluir Exemplo">
          <i class="icon-remove icon-white"></i>
        </button>
        <span class="label label-info">Exemplo <?php echo $count++; ?></span>
        <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($sentence->raw_sentence); ?></h4>
        <div class="arguments" id="arguments-<?php echo $exampleId; ?>">
        <?php
        $i = 1;
        foreach($arguments as $argument){
          $argumentId = $argument->id_argument;
          ?>
          <div id="argument-<?php echo $argument->id_argument; ?>">
            <div class="row-fluid">
              <div class="span1 offset1"><?php echo "ARG_".$i; ?></div>
              <div class="span2"><?php echo $argument->argument; ?></div>
              <div class="span2">
                <input type="text" name="syntax_<?php echo $argument->id_argument; ?>" value="<?php echo $argument->sintax; ?>" />
              </div>
              <div class="span3">
                <select name="role_<?php echo $argumentId; ?>" id ="role_argument_<?php echo $argumentId; ?>">
                  <option value="">Selecione</option>
                  <?php
                  $semantic = $argument->semantic;
                  foreach($roles as $role => $description){
                    $selected = $role == $semantic ? 'selected' : '';
                    ?>
                      <option value="<?php echo $role; ?>" <?php echo $selected; ?>><?php echo $description; ?></option>
                    <?php
                  }
                  ?>
                </select>
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
        <a class="btn btn-warning" title="Voltar para os frames" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'page' => $framePage, 'verbPage' => $verbPage, 'verbId' => $verb->id_verb)); ?>">Voltar para os frames</a>
      </div>
    </div>
  </div>  
</div>
