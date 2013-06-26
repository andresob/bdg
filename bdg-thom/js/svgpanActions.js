$(document).ready(function() {
    $('g').each(function(){
        var id = $(this).attr('id');
        pan_atualiza(id, 'svgmap');
    });
});

function pan_atualiza(id, svgid){
    $('#'+svgid).svgPan(id, true, true, false, 5);
}

