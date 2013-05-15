<?php
$template->setTitle('frame.list');
$template->addJs('example.js');
$template->addTextJs('var corpus = "'.$corpus.'";');

$pagination = $template->paginate($page, $count, 'frame-list', array('corpus' => $corpus, 'verbId' => $verb->id_verb, 'verbPage' => $verbPage));
?>
  <script>
    var corpus = "<?php echo $corpus; ?>";
  </script>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Exemplos do frame '<?php echo $frame->frame; ?>' do verbo '<?php echo $$verb->verb; ?>'</h1>
        <a class="btn btn-warning" title="Voltar para os frames" href="frames.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>&verb_page=<?php echo $verb_page;?>&verb=<?php echo $verb; ?>">Voltar para os frames</a>
        <?php paginate($page, $total, 'examples.php', 'corpus='.$corpus.'&frame='.$frame.'&frame_page='.$frame_page.'&verb_page='.$verb_page.'&verb='.$verb);?>
      </div>
    </div>
  <div class="accordion" id="examples">
  <?php
  $e = $min + 1;
  foreach($examples as $id => $example){
    $id_example = $example['id_example'];
    ?>  
    <div class="accordion-group" id="<?php echo $id_example; ?>">
      <div class="accordion-heading">
        <button type="button" class="btn btn-danger btn-mini delete-example" title="Excluir Exemplo">
          <i class="icon-remove icon-white"></i>
        </button>
        <span class="label label-info">Exemplo <?php echo $e++; ?></span>
        <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($example['raw_sentence']); ?></h4>
        <?php
            $query = "  SELECT * FROM arguments WHERE id_example = {$id_example} AND active = 1 order by relevance;";
            $arguments = mysql_query($query);
            ?>
            <div class="arguments" id="arguments-<?php echo $id_example; ?>">
            <?php
            $i = 1;
            while($argument = mysql_fetch_array($arguments)){
              ?>
              <div id="argument-<?php echo $argument['id_argument']; ?>">
                <div class="row-fluid">
                  <div class="span1 offset1"><?php echo "ARG_".$i; ?></div>
                  <div class="span2"><?php echo $argument['argument']; ?></div>
                  <div class="span2">
                    <input type="text" name="syntax_<?php echo $argument['id_argument']; ?>" value="<?php echo $argument['sintax']; ?>" />
                  </div>
                  <div class="span3">
                    <?php is_null($argument['semantic']) ? select('role_'.$argument['id_argument'], $roles, '') : select('role_'.$argument['id_argument'], $roles, $argument['semantic']); ?>
                  </div>
                  <div class="span3">
                    <input type="button" title="Salvar Argumento" class="btn btn-info save-argument" id_argument="<?php echo $argument['id_argument']; ?>" value="Salvar">
                    <input type="button" title="Excluir Argumento" class="btn btn-danger delete-argument" id_argument="<?php echo $argument['id_argument']; ?>" value="Excluir">
                  </div>
                </div>
              </div>
              <?php
              $i++;
            }
            ?>
      </div>
      <div id="example<?php echo $id_example; ?>" class="accordion-body collapse in">
        <div class="accordion-inner">     
            <span class="label label-info">Anota&ccedil;&atilde;o</span>
            <?php echo_pre($example['parsed_sentence']); ?>
          </div>
        </div>
      </div>
    </div>
  <?php      
   
    }
  ?>
  <div class="row-fluid">
      <div class="span12 well text-center">
        <a class="btn btn-warning" title="Voltar para os frames" href="frames.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>&verb_page=<?php echo $verb_page;?>&verb=<?php echo $verb; ?>">Voltar para os frames</a>
        <?php paginate($page, $total, 'examples.php', 'corpus='.$corpus.'&frame='.$frame.'&frame_page='.$frame_page.'&verb_page='.$verb_page.'&verb='.$verb);?>
      </div>
    </div>
  </div>  
</div>
?>
