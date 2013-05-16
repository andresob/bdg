$(document).ready(function() {
    $('a.recupera_consulta').live('click', function(){
        var id = $(this).parent().parent().attr('id');
        showbyid(id);
        //alert(id);
        return false;
    });

    $('a.apaga_consulta').live('click', function(){
        var id = $(this).parent().parent().attr('id');
        $.ajax({
            url: 'lib/ajax/apaga.php',
            type: 'POST',
            data: {consulta: id},
            dataType: 'json',
            success: function(json) {
                removebyid(id);
            },
            error: function(erro){
                alert("Erro na comunicação com o site");
            }
        });
        var ids = getFirst();
        showbyid(ids);
        return false;
    });
});