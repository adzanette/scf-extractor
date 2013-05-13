<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";

$frame = $_GET['frame'];
$idVerb = $_GET['verb'];

$query = "select * from sentences where id_sentence in (
          SELECT id_sentence 
          FROM examples
          WHERE id_semantic_frame = (
            SELECT id_frame
            FROM semantic_frames
            WHERE frame = '{$frame}' and id_verb = '{$idVerb}'
          ))";

$examples = mysql_query($query);

?>
<html>
<head>
  <title>Exemplos</title>
  <link href="css/base.css" rel="stylesheet" type="text/css">
</head>
<body>
  <?php
  while($example = mysql_fetch_array($examples)){
    echo $example['raw_sentence']."<br><center>--------------------------------------------</center><br>";
  }
  ?>
</body>
</html>
<?php
mysql_close($conn);
?>
