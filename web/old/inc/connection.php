<?php
$conn = mysql_connect("localhost","root","zanette");
if (!$conn) die('Não conseguiu conectar ao banco de dados. ' . mysql_error());
mysql_select_db("scf-".$corpus, $conn) or die(mysql_error());
?>
