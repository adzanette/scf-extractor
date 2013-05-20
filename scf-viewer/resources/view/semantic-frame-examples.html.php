<?php
$template->setTitle('example.list');
?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1><?php echo $template->translate('semantic.frames.examples', array('frame' => htmlentities($frame), 'verb' => $verb->verb)); ?></h1>
      <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-verbs', array('corpus' => $corpus, 'framePage' => $framePage, 'frame' => urlencode($frame)));?>"><?php echo $template->translate('verb.list.back'); ?></a>
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
          <span class="label label-info"><?php echo $template->translate('example.number', array('i' => $e++)); ?></span>
          <h4>&nbsp;&nbsp;&nbsp;<?php echo $sentence->raw_sentence; ?></h4>
        </div>
        <div id="example<?php echo $id; ?>" class="accordion-body collapse in">
          <div class="accordion-inner">     
              <span class="label label-info"><?php echo $template->translate('annotation')?></span>
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
      <a class="btn btn-warning" href="semanticFramesVerbs.php?corpus=<?php echo $corpus; ?>&frame_page=<?php echo $frame_page; ?>&frame=<?php echo $frame; ?>"><?php echo $template->translate('verb.list.back'); ?></a>
    </div>
  </div>
</div>  
