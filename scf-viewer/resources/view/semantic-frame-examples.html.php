<?php
$template->setTitle('example.list');
?>
<div class="row">
  <div class="col-md-12 well text-center">
    <h1><?php echo $template->translate('semantic.frame.list'); ?></h1>
    <h3><?php echo $template->translate('semantic.frame.subtitle', array('frame' => htmlentities($frame)))?></h3>
    <h3><?php echo $template->translate('verb.subtitle', array('verb' => $verb->verb))?></h3>
    <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-verbs', array('corpus' => $corpus, 'framePage' => $framePage, 'frame' => urlencode($frame)));?>"><?php echo $template->translate('verb.list.back'); ?></a>
  </div>
</div>
<div class="row page-list">
  <div class="col-md-10 col-md-offset-1">
    <?php
    $e = 1;
    foreach ($sentences as $sentence){
      $id = $sentence->id_sentence;
      ?>  
      <div class="accordion-group" id="<?php echo $id; ?>">
        <div class="accordion-heading">
          <span class="label label-info"><?php echo $template->translate('example.number', array('i' => $e++)); ?></span>
          <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlentities($sentence->raw_sentence); ?></h4>
        </div>
        <div id="example<?php echo $id; ?>" class="accordion-body collapse in">
          <div class="accordion-inner">     
              <span class="label label-info"><?php echo $template->translate('annotation')?></span>
              <pre><?php echo htmlentities($sentence->parsed_sentence); ?></pre>
          </div>
        </div>
      </div>
    <?php      
      }
    ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12 well text-center">
    <a class="btn btn-warning" href="<?php echo $template->getLink('semantic-frames-verbs', array('corpus' => $corpus, 'framePage' => $framePage, 'frame' => urlencode($frame)));?>"><?php echo $template->translate('verb.list.back'); ?></a>
  </div>
</div>
