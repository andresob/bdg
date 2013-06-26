function showbyid(id, l){
    $('.table-container').each(function(){
        if($(this).hasClass(id)){
            $(this).fadeIn('slow');
        }
        else $(this).fadeOut();
    });
    
    $('g').each(function(){
       if(id == $(this).attr('class')){
           $(this).fadeIn(1200).css("display", "");;
       } else if($(this).hasClass(id)){
           $(this).fadeIn(1200).css("display", "");;
       }
       else $(this).fadeOut(600).css("display", "none");
    });
    
    //$('.'+id).each(function(){$(this).fadeIn('slow');});
    //$('#'+id).each(function(){$(this).fadeIn('slow');});
    $('#tcons').val(window.atob(id));
}

var first_id_consulta = "";
function removebyid(id){
    first_id_consulta = "";
    
    var time = '500';
    $('.table-container').each(function(){
        if($(this).hasClass(id)){
            $(this).fadeOut('slow').delay(time).remove();
        }
    });
    
    $('g').each(function(){
       if(id == $(this).attr('class')) $(this).fadeOut(1200).css("display", "").remove();
    });
    
    //$('.'+id).each(function(){$(this).fadeOut('slow').delay(time).remove();});
    //$('#'+id).each(function(){$(this).fadeOut('slow').delay(time).remove();});
    $('.layer').each(function(){
        if($(this).attr('id') == id){
            $(this).fadeOut('slow').delay(time).remove();
        }
    });
    
    if($('#tcons').val() == window.atob(id)){
        $('#tcons').val('');
    }
}

function getFirst(){
    if(first_id_consulta != ""){
        $('.layer').each(function(){
            var id = $(this).attr('id');
            if(id == first_id_consulta) return first_id_consulta;
        });
    }else{
        $('.layer').each(function(){
            var id = $(this).attr('id');
            first_id_consulta = id;
            return first_id_consulta;
        });
    }
    return first_id_consulta;
}

function makeSVG(tag, attrs) {
    var el= document.createElementNS('http://www.w3.org/2000/svg', tag);
    for (var k in attrs)
        el.setAttribute(k, attrs[k]);
    return el;
}

function parseSVG(s) {
    var div= document.createElementNS('http://www.w3.org/1999/xhtml', 'div');
    div.innerHTML= '<svg xmlns="http://www.w3.org/2000/svg">'+s+'</svg>';
    var frag= document.createDocumentFragment();
    while (div.firstChild.firstChild)
        frag.appendChild(div.firstChild.firstChild);
    return frag;
}