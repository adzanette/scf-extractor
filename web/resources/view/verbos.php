<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";

$page = isset($_GET['page']) && $_GET['page'] != '' ? $_GET['page'] : 1;

$min = ($page - 1) * PAGE_SIZE; 
$max = PAGE_SIZE;

$query = " SELECT * FROM verbs where frequency > 1 ORDER BY frequency DESC, verb ASC LIMIT {$min}, {$max};";

$count_query = "SELECT count(*) as total FROM verbs where frequency > 1;";
$count = mysql_query($count_query);
$row_total =  mysql_fetch_array($count);
$total = $row_total[0];

$total_page = ($total >= ($min + PAGE_SIZE)) ? PAGE_SIZE : ($total % PAGE_SIZE);

$verbs = mysql_query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Listagem de verbos</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Listagem de verbos</h1>
        <a href="semanticFramesList.php?corpus=<?php echo $corpus; ?>" target="_blank" class="btn btn-inverse">Ver Lista de Frames Sem&acirc;nticos</a>
        <a class="btn btn-warning" title="Trocar de Corpus" href="index.php">Trocar de corpus</a>
        <?php paginate($page, $total, 'verbos.php', 'corpus='.$corpus);?>
      </div>
    </div>
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="header">
          <th>Verbo</th>
          <th>Frequ&ecirc;ncia</th>
          <th>Frames</th>
          <th>Frames Sem&acirc;nticos</th>
        </tr>
      </thead>
    <?php
    $i = 1;
    while($verb = mysql_fetch_array($verbs)){
      $last = '';
      if ($i == $total_page) $last = ' last';
      ?>
      <tr>
        <td><?php echo $verb['verb']; ?></td>
        <td><?php echo $verb['frequency']; ?></td>
        <td>
          <a class="btn btn-success" href="frames.php?corpus=<?php echo $corpus; ?>&verb=<?php echo $verb['id_verb']; ?>&verb_page=<?php echo $page; ?>">
            <span class="icon-play icon-white">
          </a>
        </td>
        <td>
          <a class="btn btn-success" href="semanticFrames.php?corpus=<?php echo $corpus; ?>&verb=<?php echo $verb['id_verb']; ?>&verb_page=<?php echo $page; ?>">
            <span class="icon-play icon-white">
          </a>
        </td>
      </tr>
    <?php
      $i++;
    }
    ?>
    </table>
    <div class="row-fluid">
      <div class="span12 well text-center">
        <?php paginate($page, $total, 'verbos.php', 'corpus='.$corpus);?>
        <a class="btn btn-warning" title="Trocar de Corpus" href="index.php">Trocar de corpus</a>
      </div>
    </div>
  </div>
</body>
</html>
<?php
mysql_close($conn);
?>
