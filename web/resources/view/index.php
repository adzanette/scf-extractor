<?php
include "inc/functions.php";

$corpora = array();
$corpora['cardiologia'] = 'Cardiologia';
$corpora['diario-gaucho'] = 'Di&aacute;rio Ga&uacute;cho';
$corpora['lacio'] = 'Lacio';
$corpora['teste'] = 'CardTeste';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Selecione um Corpus</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container-fluid">
  <div class="row-fluid">
    <div class="span12 well text-center">
      <h1>Selecione um Corpus</h1>
      <form method="get" action="verbos.php">
        <div class="input-append">
          <?php select('corpus', $corpora, 'cardiologia');?>
          <button class="btn btn-inverse" type="submit">Selecionar</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>

