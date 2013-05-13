<?php
session_start();
$_SESSION['corpus'] = $_POST['corpus'];

header('Location: verbos.php');
?>
