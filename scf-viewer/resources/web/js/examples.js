$(document).ready(function($){ 
 $(".save-argument").click(function(){
    var id = $(this).attr('id_argument'); 
    var syntax = $('input[name="syntax_'+id+'"]').val();
    var semantic = $('select[name="role_'+id+'"]').val();
    
    $.ajax({
      type: 'POST',
      url: saveArgumentUrl,
      dataType: 'json',
      data: {
        id_argument: id,
        syntax: syntax,
        semantic: semantic
      },
      success: function(data) {
        if (data.success){
          alert("Dados salvos com sucesso");
        }else{
          alert("Erro ao salvar os dados, tente novamente.");
        }
      }
    });
 });
 
 $(".delete-argument").click(function(){
    var id = $(this).attr('id_argument'); 
    $.ajax({
      type: 'POST',
      url: deleteArgumentUrl,
      dataType: 'json',
      data: {
        id_argument: id
      },
      success: function(data) {
        if (data.success){
          $("#argument-"+id).slideUp(500);
        }else{
          alert("Erro ao excluir o argumento, tente novamente.");
        }
      }
    });
 });
 
 $(".delete-example").click(function(){
    var id = $(this).parent().attr('id');
    
    $.ajax({
      type: 'POST',
      url: deleteExampleUrl,
      dataType: 'json',
      data: {
        id_example: id
      },
      success: function(data) {
        if (data.success){
          $("#"+id).slideUp(700);
        }else{
          alert("Erro ao excluir o exemplo, tente novamente.");
        }
      }
    });
 });
});