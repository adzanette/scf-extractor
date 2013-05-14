<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";


$page = isset($_GET['page']) ? $_GET['page'] : 1;

$frame = $_GET['frame'];
$frame_page = $_GET['frame_page'];
$verb = $_GET['verb'];

$min = ($page - 1) * PAGE_SIZE; 
$max = PAGE_SIZE;

$query_verb = " SELECT verb FROM verbs WHERE id_verb = {$verb};";
$result = mysql_query($query_verb);
$v =  mysql_fetch_array($result);
$verb_name = $v[0];

$query = "select sentences.* from sentences where id_sentence in (
          SELECT id_sentence 
          FROM examples
          WHERE id_semantic_frame = (
            SELECT id_frame
            FROM semantic_frames
            WHERE frame = '{$frame}' and id_verb = '{$verb}'
          ))";

$examples = mysql_query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Exemplos do frame sem&acirc;ntico '<?php echo $frame; ?>' do verbo '<?php echo $verb_name; ?>'</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-collapse.js"></script>  
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Exemplos do frame sem&acirc;ntico '<?php echo $frame; ?>' do verbo '<?php echo $verb_name; ?>'</h1>
        <a class="btn btn-warning" title="Voltar para os frames" href="semanticFramesVerbs.php?corpus=<?php echo $corpus; ?>&frame_page=<?php echo $frame_page; ?>&frame=<?php echo $frame; ?>">Voltar para os verbos</a>
      </div>
    </div>
    <div class="accordion" id="examples">
      <?php
      $e = 1;
      while($example = mysql_fetch_array($examples)){
        $id_example = $example['id_sentence'];
        ?>  
        <div class="accordion-group" id="<?php echo $id_example; ?>">
          <div class="accordion-heading">
            <span class="label label-info">Exemplo <?php echo $e++; ?></span>
            <h4>&nbsp;&nbsp;&nbsp;<?php echo $example['raw_sentence']; ?></h4>
          </div>
          <div id="example<?php echo $id_example; ?>" class="accordion-body collapse in">
            <div class="accordion-inner">     
                <span class="label label-info">Anota&ccedil;&atilde;o</span>
                <?php echo '<pre>'.$example['parsed_sentence'].'</pre>'; ?>
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
</body>
</html>
<?php
mysql_close($conn);
?>
