<?php
if(isset($_GET['corpus'])  && $_GET['corpus'] != ''){
  $corpus = $_GET['corpus'];
}else{
  echo "0";
  exit();
}

include "../inc/connection.php";

$id = $_GET['id_example'];

$query = "  UPDATE examples SET active = 0 WHERE id_example = '{$id}';";
$result = mysql_query($query);

echo $result ? "1" : "0";

?>
