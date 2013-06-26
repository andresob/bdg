$(document).ready(function() {
    
    $('.colorSelector').each(function(){
        var id = $(this).attr('id');
        color_picker_show(id)
    });
    
});

function color_picker_show(id){
    var color_picker_id_selected = '';
    $('#'+id).ColorPicker({
        color: '#0000ff',
        onShow: function (colpkr) {
                color_picker_id_selected = $(this).attr('id');
                $(colpkr).fadeIn(500);
                return false;
        },
        onHide: function (colpkr) {
                color_picker_id_selected = "";
                $(colpkr).fadeOut(500);
                return false;
        },
        onChange: function (hsb, hex, rgb) {
                var temp = '#'+color_picker_id_selected+' div';
                $(temp).css('backgroundColor', '#' + hex);
        }
    });
}