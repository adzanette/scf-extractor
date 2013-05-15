<?php
if(isset($_GET['corpus'])  && $_GET['corpus'] != ''){
  $corpus = $_GET['corpus'];
}else{
  echo "0";
  exit();
}

include "../inc/connection.php";

$id = $_GET['id_argument'];

$query = "  UPDATE arguments SET active = 0 WHERE id_argument = '{$id}';";
$result = mysql_query($query);

echo $result ? "1" : "0";

?>
