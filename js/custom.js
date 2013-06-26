var current_query = "";
var default_query_text = "Digite sua consulta SQL";

$(document).ready(function () {

    $("[rel=tooltip]").tooltip();

    $(".autogrow").autogrow();
    
    $('#tabbed-widget a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$('.table-data').dataTable();

	var query_textarea = $('#query');

	query_textarea.focus(function() {
		var newQuery = highlightSQL(current_query);
		query_textarea.html(newQuery);
	});

	query_textarea.blur(function() {
		query_textarea.html(default_query_text);
	});

	query_textarea.keyup(function(e) {
		var sql = query_textarea.html();
		current_query = strip_tags(sql, "<br>");

		// var code = (e.keyCode ? e.keyCode : e.which);
		// consoleLog(code);
		// if(code == 32) { //spacebar
		// 	var newQuery = highlightSQL(query_textarea.html());
		// 	query_textarea.html(newQuery);
		// 	moveCaretToEnd(query_textarea);
		// }

	});

	query_textarea.html(default_query_text);

   
  });

function consoleLog(msg) { console.log(msg); }

function highlightSQL(text) {

	var SQLWORDS = ["select", "from", "where", "and", "or", "in", "limit", "order", "by", "group", "union"];
	var words = text.split(' ');

	for (var i = 0; i < words.length; i++) {
		var word = words[i].toLowerCase();
		if($.inArray(word, SQLWORDS) !== -1) {
			words[i] = "<span class='highlightSQL'>"+words[i]+"</span>";
		}
	};

	return words.join(' ');

}

function strip_tags (input, allowed) {
    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}