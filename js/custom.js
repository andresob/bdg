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
		query_textarea.html(current_query);
	});

	query_textarea.blur(function() {
		query_textarea.html(default_query_text);
	});

	query_textarea.keyup(function(e) {
		var sql = query_textarea.html();
		current_query = strip_tags(sql, "<br>");

		analyseQuery();

	});

	query_textarea.html(default_query_text);

	$('#schema_tables .schema_table').wookmark({container: $("#schema_tables"), offset: 10});
	$(window).resize(function() {
		$('#schema_tables .schema_table').wookmark({container: $("#schema_tables"), offset: 10});
	});
   
  });

function consoleLog(msg) { console.log(msg); }

function analyseQuery() {
	var q = replaceAll("<br>", " ", current_query);
	q = replaceAll("<br/>", " ", q);
	q = replaceAll("&nbsp", " ", q);

	$("#schema_tables .selected").removeClass('selected');
	var analysis = simpleSqlParser.sql2ast(q);

	console.log(analysis);

	if(typeof analysis.FROM !== "undefined" && analysis.FROM.length > 0) {
		for(var i in analysis.FROM) {
			var table = $.trim(analysis.FROM[i].table);
			table = table.split(" ");
			table = table[0];
			$("#schema_tables [data-table='"+table+"']").addClass('selected');
		}

		if(typeof analysis.SELECT !== "undefined" && analysis.SELECT.length > 0) {
			for(var i in analysis.SELECT) {
				var column = analysis.SELECT[i];
				if(column == "*") {
					$("#schema_tables [data-table='"+table+"'] [data-column]").addClass('selected');
				} else {
					$("#schema_tables [data-table='"+table+"'] [data-column='"+column+"']").addClass('selected');
				}
			}
		}
	} else if(typeof analysis.SELECT !== "undefined" && analysis.SELECT.length > 0) {
		for(var i in analysis.SELECT) {
			var column = analysis.SELECT[i];
			if(column == "*") {
				$("#schema_tables [data-column]").addClass('selected');
			} else {
				$("#schema_tables  [data-column='"+column+"']").addClass('selected');
			}
		}
	}
}

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

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}