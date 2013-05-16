$(document).ready(function(){
    
    $('#form').submit(function() {
        
          //recupera o id do item e faz um base62 decode
          var id = window.btoa($('#tcons').val());
          var contains = 0; //variavel que indica se a consulta já está na tela
          
          //procura na tela a consulta realizada
          $('#sortable a').each(function(){
              if($(this).parent().attr('id') == id){
              //if($(this).attr('href') == id){
                  //se encontrou faz o item piscar
                  $(this).parent().fadeOut('fast').fadeIn('fast');
                  contains = 1;
              }
          });
          
          //esconde 
          $('.table-container').each(function(){
              $(this).fadeOut('slow');
          });
          $('g').each(function(){
              $(this).fadeOut('slow');
          });
          
          if(contains == 0){
              $.ajax({
                    url: $('#'+form.id).attr('action'),
                    type: 'POST',
                    data: $('#'+form.id).serialize(),
                    dataType: 'json',
                    success: function(json) {
                        var g = parseSVG(json.svgmap);
                        $('#svgmap').append(g);
                        json.svgmap = "";
                        
                        for(var i in json){
                           if(json[i] != "")$("#"+i).append(json[i]);
                        }
                        showbyid(id, 'l');
                        jpickeraction("colorSelector", '.');
                    },
                    error: function(erro){
                        alert("Erro na comunicação com o site");
                    }
              });
          }
          showbyid(id);
          return false;
          
    });
    
    
    $('.layer').each(function(){
        var id = $(this).attr('id');
        $('.'+id).each(function(){
            if($(this).hasClass("layer")){}
            else $(this).fadeOut();
        });
        $('#'+id).each(function(){
            if($(this).hasClass("layer")){}
            else $(this).fadeOut();
        });
    });
    
    var primeiro_layer = getFirst();
    showbyid(primeiro_layer);
});

$('.selecionar').live('click', function(){
    //alert($(this).attr('id'));
    $(this).removeClass('selecionar');
    $(this).addClass('select');
    $(this).addClass('select');
    $(this).children(".item").removeClass('bg');
    $(this).children(".item").addClass('ativo');
    
    $('.selecionar').each(function(index) {
        $(this).removeClass("consultaativa");
    });
    $(this).addClass("consultaativa");
    return false;
});

$('.select').live('click', function(){
    $(this).removeClass('select');
    $(this).addClass('selecionar');
    $(this).children(".item").removeClass('ativo');
    $(this).children(".item").addClass('bg');
    
    $('.select').each(function(index) {
        $(this).removeClass("consultaativa");
    });
    $(this).addClass("consultaativa");
    return false;
});

$(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
});


$('#merge').live('click', function(){
    var varr = 'merge.php?action=merge&consult=';
    $('.ativo').each(function(index) {
        varr += $(this).parent().parent().attr('id') + ";";
    });
    $(this).attr('href', varr);
});
