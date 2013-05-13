<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$min = ($page - 1) * PAGE_SIZE; 
$max = PAGE_SIZE;

$query = " SELECT frame, SUM(frequency) AS total FROM semantic_frames GROUP BY frame ORDER BY total DESC  LIMIT {$min}, {$max};";

$count_query = "SELECT count(*) as total FROM (SELECT DISTINCT(frame) FROM semantic_frames) as frames;";
$count = mysql_query($count_query);
$row_total =  mysql_fetch_array($count);
$total = $row_total[0];

$total_page = ($total >= ($min + PAGE_SIZE)) ? PAGE_SIZE : ($total % PAGE_SIZE);

$frames = mysql_query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Frames Sem&acirc;nticos do verbo <?php echo $verb_name; ?></title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Frames Sem&acirc;nticos</h1>
        <a class="btn btn-warning" title="Voltar para os verbos" href="verbos.php?corpus=<?php echo $corpus; ?>">Voltar para os verbos</a>
        <?php paginate($page, $total, 'semanticFramesList.php', 'corpus='.$corpus);?>
      </div>
    </div>
    <table class="table table-bordered table-hover">
    <thead>
      <tr class="header">
        <th>Frame</th>
        <th>Frequ&ecirc;ncia</th>
        <th>Ver Verbos</th>
      </tr>
    </thead>
  <?php
  while($frame = mysql_fetch_array($frames)){
    ?>
    <tr>
      <td><?php echo htmlspecialchars($frame['frame']); ?></td>
      <td><?php echo $frame['total']; ?></td>
      <td>
        <a class="btn btn-success" title="Ver Verbos" href="semanticFramesVerbs.php?corpus=<?php echo $corpus; ?>&frame=<?php echo urlencode($frame['frame']); ?>&frame_page=<?php echo $page; ?>">
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
        <?php paginate($page, $total, 'semanticFramesList.php', 'corpus='.$corpus);?>
        <a class="btn btn-warning" title="Voltar para os verbos" href="verbos.php?corpus=<?php echo $corpus; ?>">Voltar para os verbos</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
mysql_close($conn);
?>
