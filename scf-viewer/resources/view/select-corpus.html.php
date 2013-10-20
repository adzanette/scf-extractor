<?php
$template->setTitle('select.corpus');
?>
<div class="row">
    <div class="row  well text-center">
      <h1><?php echo $template->translate('select.a.corpus')?></h1>
      <form method="post" action="<?php echo $template->getLink('select-corpus'); ?>">
        <div class="input-group col-lg-4 col-lg-offset-4">
          <select id="corpus" name="corpus" class="form-control">
            <option value=""><?php echo $template->translate('default.select')?></option>
            <?php 
              foreach ($databases as $database => $name){
              ?>
                <option value="<?php echo $database; ?>"><?php echo $template->translate($name); ?></option>
              <?php
              }
            ?>
          </select>
          <span class="input-group-btn">
            <button class="btn btn-info" type="submit"><?php echo $template->translate('select')?></button>
          </span>
        </div>
      </form>
    </div>
</div>

