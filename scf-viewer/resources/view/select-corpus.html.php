<?php
$template->setTitle('select.corpus');
?>
  
    <div class="container">
      <div class="row well text-center">
        <h1><?php echo $template->translate('select.a.corpus')?></h1>
        <form role="form" method="post" action="<?php echo $template->getLink('select-corpus'); ?>">
          <div class="input-group col-md-4 col-md-offset-4">
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
