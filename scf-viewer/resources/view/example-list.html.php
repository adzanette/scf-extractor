<?php
$template->setTitle('example.list');
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
        <h1><?php echo $template->translate('example.title', array('verb' => $verb->verb, 'frame' => $frame->frame))?></h1>
        <a class="btn btn-warning" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'page' => $framePage, 'verbPage' => $verbPage, 'verbId' => $verb->id_verb)); ?>"><?php echo $template->translate('frame.list.back')?></a>
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
        <button type="button" class="btn btn-danger btn-mini delete-example" title="<?php echo $template->translate('delete-example')?>">
          <i class="icon-remove icon-white"></i>
        </button>
        <span class="label label-info"><?php echo $template->translate('example.number', array('i' => $count++))?></span>
        <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($sentence->raw_sentence); ?></h4>
        <div class="arguments" id="arguments-<?php echo $exampleId; ?>">
        <?php
        $i = 1;
        foreach($arguments as $argument){
          $argumentId = $argument->id_argument;
          ?>
          <div id="argument-<?php echo $argument->id_argument; ?>">
            <div class="row-fluid">
              <div class="span1 offset1"><?php echo $template->translate('argument.number', array('i' => $i))?></div>
              <div class="span2"><?php echo $argument->argument; ?></div>
              <div class="span2">
                <input type="text" name="syntax_<?php echo $argument->id_argument; ?>" value="<?php echo $argument->sintax; ?>" />
              </div>
              <div class="span3">
                <select name="role_<?php echo $argumentId; ?>" id ="role_argument_<?php echo $argumentId; ?>">
                  <option value=""><?php echo $template->translate('select.role')?></option>
                  <?php
                  $semantic = $argument->semantic;
                  foreach($roles as $role => $description){
                    $selected = $role == $semantic ? 'selected' : '';
                    ?>
                      <option value="<?php echo $role; ?>" <?php echo $selected; ?>><?php echo $template->translate($description); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="span3">
                <button class="btn btn-info save-argument" id_argument="<?php echo $argument->id_argument; ?>"><?php echo $template->translate('argument.save'); ?></button>
                <button class="btn btn-danger delete-argument" id_argument="<?php echo $argument->id_argument; ?>"><?php echo $template->translate('argument.delete'); ?></button>
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
            <span class="label label-info"><?php echo $template->translate('annotation'); ?></span>
            <style>
              #example<?php echo $exampleId; ?> #token-id-<?php echo $example->position; ?>{
                background-color: rgb(255, 158, 69);
              }
            </style>
            <?php 
            //echo $sentence->html_sentence; 
            ?>
            <pre><?php echo htmlentities($sentence->parsed_sentence); ?></pre>
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
        <a class="btn btn-warning" href="<?php echo $template->getLink('frame-list', array('corpus' => $corpus, 'page' => $framePage, 'verbPage' => $verbPage, 'verbId' => $verb->id_verb)); ?>"><?php echo $template->translate('frame.list.back')?></a>
      </div>
    </div>
  </div>  
</div>
