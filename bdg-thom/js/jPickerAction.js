$(document).ready(function() {
    jpickeraction("colorSelector", '.');
});

function jpickeraction(id, carc){
    var cid   = carc+id;
    
    //var color = getColor() + '99';
    $(cid).jPicker({
        window:{
            alphaSupport: true,
            expandable: true,
            position:{x: 'screenCenter',y: 'screenCenter'}
        },
        color:{
            
            active: new $.jPicker.Color({ahex: '993300CC'})
        },
        images: {clientPath: 'plugins/jpicker/images/'}
    }, 
    
    function(color, context){
        
        var all    = color.val('all');
        var cvalue = (all && '#' + all.hex || 'none');
        var alpha  = (all && all.a + '%' || 'none');
        var id     = $(this).parent().parent().attr('id');
        var classe = '';
        
        if($(this).hasClass( 'bgcolor' )){
            classe = 'bgcolor';
            $.ajax({
                url: 'lib/ajax/savecolor.php',
                type: 'POST',
                data: {stroke: cvalue, stroke_opacity: alpha, id: id},
                dataType: 'json',
                error: function(erro){
                    alert("Erro na comunicação com o site");
                }
            });
        }else if($(this).hasClass( 'licolor' )){
            classe = 'licolor';
            $.ajax({
                url: 'lib/ajax/savecolor.php',
                type: 'POST',
                data: {fill: cvalue, fill_opacity: alpha, id: id},
                dataType: 'json',
                success: function(json) {
                    
                },
                error: function(erro){
                    alert("Erro na comunicação com o site");
                }
            });
        }
        
        $('g').each(function(){
           if(id == $(this).attr('class')){
               if(classe == 'bgcolor'){
                    $(this).attr('stroke', cvalue);
                    $(this).attr('stroke-opacity', alpha);
               }else if(classe == 'licolor'){
                   $(this).attr('fill', cvalue);
                   $(this).attr('fill-opacity', alpha);
               }
               return;
           } 
        });

    });
    
    $(cid).each(function(){
        if($(this).hasClass('colorSelector')){
            $(this).addClass('jpicker_active');
            $(this).removeClass('colorSelector');
        }
    });
}

function getColor(){
    var randomColor = Math.floor(Math.random()*16777215).toString(16);
    return randomColor;
}