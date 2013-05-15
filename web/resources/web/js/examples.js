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