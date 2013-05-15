<?php
$template->setTitle('select.corpus');

$template->paginate(1, $count, 'verb-list', array('corpus' => 'scf-teste'));

?>
ashdjashduashudhuasuasduhasduhasudhusadhuasdhuas
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1><?php echo $template->translate('select.a.corpus')?></h1>
      <form method="get" action="verbos.php">
        <div class="input-append">
          <select id="corpus" name="corpus">
            <option value=""><?php echo $template->translate('default.select')?></option>
            <?php 
              foreach ($databases as $database => $name){
              ?>
                <option value="<?php echo $database; ?>"><?php echo $template->translate($name); ?></option>
              <?php
              }
            ?>
          </select>
          <button class="btn btn-inverse" type="submit"><?php echo $template->translate('select')?></button>
        </div>
      </form>
    </div>
  </div>
</div>

