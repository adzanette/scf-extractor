<?php
$corpus = isset($_GET['corpus']) ? $_GET['corpus'] : die('Nenhum corpus selecionado. <a href="index.php">Voltar</a>');

include "inc/functions.php";
include "inc/connection.php";


$page = isset($_GET['page']) ? $_GET['page'] : 1;

$frame = $_GET['frame'];
$frame_page = $_GET['frame_page'];
$verb_page = $_GET['verb_page'];
$verb = $_GET['verb'];

$min = ($page - 1) * PAGE_SIZE; 
$max = PAGE_SIZE;

$query_verb = " SELECT verb FROM verbs WHERE id_verb = {$verb};";
$result = mysql_query($query_verb);
$v =  mysql_fetch_array($result);
$verb_name = $v[0];

$query_frame = " SELECT frame FROM frames WHERE id_frame = {$frame};";
$result = mysql_query($query_frame);
$f =  mysql_fetch_array($result);
$frame_name = $f[0];

$query = "  SELECT e.*, s.* 
            FROM examples e INNER JOIN sentences s ON e.id_sentence = s.id_sentence
            WHERE id_frame = {$frame} AND e.active = 1 LIMIT {$min}, {$max};";

$count_query = "SELECT count(*) as total FROM examples WHERE id_frame = {$frame} AND active = 1;";
$count = mysql_query($count_query);
$row_total =  mysql_fetch_array($count);
$total = $row_total[0];

$examples = mysql_query($query);

$roles = get_roles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Frames do verbo <?php echo $verb_name; ?></title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/base.css" rel="stylesheet" type="text/css">
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-collapse.js"></script>  
  <script>
    var corpus = "<?php echo $corpus; ?>";
    $(document).ready(function($){ 
     $(".save-argument").click(function(){
        var id = $(this).attr('id_argument'); 
        var syntax = $('input[name="syntax_'+id+'"]').val();
        var semantic = $('select[name="role_'+id+'"]').val();
        
        $.ajax({
          type: 'GET',
          url: 'ajax/save_argument.php',
          data: {
            id_argument: id,
            syntax: syntax,
            semantic: semantic,
            corpus: corpus
          },
          success: function(data) {
            if (data == '1'){
              alert("Dados salvos com sucesso");
            }else{
              alert("Erro ao salvar os dados, tente novamente.");
            }
          },
          dataType: 'html'
        });
     });
     
     $(".delete-argument").click(function(){
        var id = $(this).attr('id_argument'); 
        $.ajax({
          type: 'GET',
          url: 'ajax/delete_argument.php',
          data: {
            id_argument: id,
            corpus: corpus
          },
          success: function(data) {
            if (data == '1'){
              $("#argument-"+id).slideUp(500);
            }else{
              alert("Erro ao excluir o argumento, tente novamente.");
            }
          },
          dataType: 'html'
        });
     });
     
     $(".delete-example").click(function(){
        var id = $(this).parent().parent().attr('id');
        
        $.ajax({
          type: 'GET',
          url: 'ajax/delete_example.php',
          data: {
            id_example: id,
            corpus: corpus
          },
          success: function(data) {
            if (data == '1'){
              $("#"+id).slideUp(700);
            }else{
              alert("Erro ao excluir o exemplo, tente novamente.");
            }
          },
          dataType: 'html'
        });
     });
   });
  </script>
</head>
<body>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12 well text-center">
        <h1>Exemplos do frame '<?php echo $frame_name; ?>' do verbo '<?php echo $verb_name; ?>'</h1>
        <a class="btn btn-warning" title="Voltar para os frames" href="frames.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>&verb_page=<?php echo $verb_page;?>&verb=<?php echo $verb; ?>">Voltar para os frames</a>
        <?php paginate($page, $total, 'examples.php', 'corpus='.$corpus.'&frame='.$frame.'&frame_page='.$frame_page.'&verb_page='.$verb_page.'&verb='.$verb);?>
      </div>
    </div>
  <div class="accordion" id="examples">
  <?php
  $e = $min+1;
  while($example = mysql_fetch_array($examples)){
    $id_example = $example['id_example'];
    ?>  
    <div class="accordion-group" id="<?php echo $id_example; ?>">
      <div class="accordion-heading">
        <button type="button" class="btn btn-danger btn-mini delete-example" title="Excluir Exemplo">
          <i class="icon-remove icon-white"></i>
        </button>
        <span class="label label-info">Exemplo <?php echo $e++; ?></span>
        <h4>&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($example['raw_sentence']); ?></h4>
        <?php
            $query = "  SELECT * FROM arguments WHERE id_example = {$id_example} AND active = 1 order by relevance;";
            $arguments = mysql_query($query);
            ?>
            <div class="arguments" id="arguments-<?php echo $id_example; ?>">
            <?php
            $i = 1;
            while($argument = mysql_fetch_array($arguments)){
              ?>
              <div id="argument-<?php echo $argument['id_argument']; ?>">
                <div class="row-fluid">
                  <div class="span1 offset1"><?php echo "ARG_".$i; ?></div>
                  <div class="span2"><?php echo $argument['argument']; ?></div>
                  <div class="span2">
                    <input type="text" name="syntax_<?php echo $argument['id_argument']; ?>" value="<?php echo $argument['sintax']; ?>" />
                  </div>
                  <div class="span3">
                    <?php is_null($argument['semantic']) ? select('role_'.$argument['id_argument'], $roles, '') : select('role_'.$argument['id_argument'], $roles, $argument['semantic']); ?>
                  </div>
                  <div class="span3">
                    <input type="button" title="Salvar Argumento" class="btn btn-info save-argument" id_argument="<?php echo $argument['id_argument']; ?>" value="Salvar">
                    <input type="button" title="Excluir Argumento" class="btn btn-danger delete-argument" id_argument="<?php echo $argument['id_argument']; ?>" value="Excluir">
                  </div>
                </div>
              </div>
              <?php
              $i++;
            }
            ?>
      </div>
      <div id="example<?php echo $id_example; ?>" class="accordion-body collapse in">
        <div class="accordion-inner">     
            <span class="label label-info">Anota&ccedil;&atilde;o</span>
            <?php echo_pre($example['parsed_sentence']); ?>
          </div>
        </div>
      </div>
    </div>
  <?php      
   
    }
  ?>
  <div class="row-fluid">
      <div class="span12 well text-center">
        <a class="btn btn-warning" title="Voltar para os frames" href="frames.php?corpus=<?php echo $corpus; ?>&page=<?php echo $frame_page; ?>&verb_page=<?php echo $verb_page;?>&verb=<?php echo $verb; ?>">Voltar para os frames</a>
        <?php paginate($page, $total, 'examples.php', 'corpus='.$corpus.'&frame='.$frame.'&frame_page='.$frame_page.'&verb_page='.$verb_page.'&verb='.$verb);?>
      </div>
    </div>
  </div>  
</div>
</body>
</html>
<?php
mysql_close($conn);
?>
