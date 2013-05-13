<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$verb = $_GET['verb'];
$verb_page = $_GET['verb_page'];

$min = ($page - 1) * PAGE_SIZE; 
$max = PAGE_SIZE;

$query_verb = " SELECT verb FROM verbs WHERE id_verb = {$verb};";
$result = mysql_query($query_verb);
$v =  mysql_fetch_array($result);
$verb_name = $v[0];

$query = " SELECT * FROM semantic_frames WHERE id_verb = {$verb}  ORDER BY frequency DESC, frame ASC LIMIT {$min}, {$max};";

$count_query = "SELECT count(*) as total FROM semantic_frames WHERE id_verb = {$verb};";
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
        <h1>Frames do verbo '<?php echo $verb_name; ?>'</h1>
        <a class="btn btn-warning" title="Voltar para os verbos" href="verbos.php?corpus=<?php echo $corpus; ?>&page=<?php echo $verb_page; ?>">Voltar para os verbos</a>
        <?php paginate($page, $total, 'semanticFrames.php', 'corpus='.$corpus.'&verb='.$verb.'&verb_page='.$verb_page);?>
      </div>
    </div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="header">
          <th>Frame</th>
          <th>Frequ&ecirc;ncia</th>
        </tr>
      </thead>
      <?php
      while($frame = mysql_fetch_array($frames)){
        ?>
        <tr>
          <td><?php echo htmlspecialchars($frame['frame'], ENT_QUOTES); ?></td>
          <td><?php echo $frame['frequency']; ?></td>
        </tr>
        <?php
      }
      ?>
    </table>
    <div class="row-fluid">
      <div class="span12 well text-center">
      <?php paginate($page, $total, 'semantiFrames.php', 'corpus='.$corpus.'&verb='.$verb.'&verb_page='.$verb_page);?>
      <a class="btn btn-warning" title="Voltar para os verbos" href="verbos.php?corpus=<?php echo $corpus; ?>&page=<?php echo $verb_page; ?>">Voltar para os verbos</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
mysql_close($conn);
?>