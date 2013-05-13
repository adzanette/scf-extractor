<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";

$frame = $_GET['frame'];
$frame_page = $_GET['frame_page'];

$query = "SELECT v.*, 
            (select frequency from semantic_frames as sf where frame = '{$frame}' and sf.id_verb = v.id_verb) as frequency
          FROM verbs as v
          WHERE id_verb in (
            SELECT id_verb
            FROM semantic_frames
            WHERE frame = '{$frame}'
          ) order by frequency desc";

$verbs = mysql_query($query);
$total = mysql_num_rows($verbs);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Verbos com o Frame Sem&acirc;ntico <?php echo $frame; ?></title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <script src="js/bootstrap.min.js"></script>
 </head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Verbos com o Frame Sem&acirc;ntico <?php echo $frame; ?></h1>
        <a class="btn btn-warning" title="Voltar para os verbos" href="semanticFramesList.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>">Voltar para os frames</a>
      </div>
    </div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="header">
          <th>Verbo</th>
          <th>Frequ&ecirc;ncia</th>
          <th>Exemplos</th>
        </tr>
      </thead>
  <?php
  while($verb = mysql_fetch_array($verbs)){
    ?>
    <tr>
      <td><?php echo $verb['verb']; ?></td>
      <td><?php echo $verb['frequency']; ?></td>
      <td>
        <a class="btn btn-success"  title="Ver Exemplos" href="<?php echo 'semanticFramesExamples.php?corpus='.$corpus.'&frame='.urlencode($frame).'&verb='.$verb['id_verb'].'&frame_page='.$frame_page; ?>">
          <span class="icon-play icon-white">
        </a>
      </td>
    </tr>
  <?php
  }
  ?>
    </table>
    <div class="row-fluid">
      <div class="span12 well text-center">
        <a class="btn btn-warning" title="Voltar para os verbos" href="semanticFramesList.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>">Voltar para os frames</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
mysql_close($conn);
?>
