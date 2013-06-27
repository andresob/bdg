var current_query = "";
var default_query_text = "Digite sua consulta SQL";
var cached_queries = [];

/*********** AFTER PAGE IS LOADED ************/

$(document).ready(function () {

    $("[rel='tooltip']").tooltip({placement: "bottom"});

    $(".autogrow").autogrow();
    
    $('#tabbed-widget a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$('.table-data').dataTable();

	var query_textarea = $('#query');
	var query_spacer = $("#query_spacer");
	var query_wrapper = $("#query_wrapper");
	var schema_tables = $("#schema_tables");
	var execute_query = $("#execute_query");

	query_textarea.focus(function() {
	 	if(query_textarea.html() == default_query_text) query_textarea.html('');
	 	query_spacer.height(query_textarea.outerHeight() + 50);
	});

	query_textarea.blur(function() {
		if(query_textarea.html() == "") query_textarea.html(default_query_text);
		query_spacer.height(query_textarea.outerHeight() + 50);
	});

	query_spacer.height(query_textarea.outerHeight() + 50);

	query_textarea.keyup(function(e) {


		var sql = query_textarea.html();
		current_query = strip_tags(sql, "<br>");

		query_spacer.height(query_textarea.outerHeight() + 50);
		var a = analyseQuery();

		//var sql_analysis = simpleSqlParser.ast2sql(a);
		//console.log(sql_analysis);

	});

	schema_tables.find(".schema_table .schema_title").click(function() {

		$("#schema_tables .selected2").removeClass('selected2');
		var table = $(this).parent();

		if(table.hasClass('selected')) {
			table.removeClass('selected');
			table.find('.selected').removeClass('selected');
		} else {
			table.addClass('selected');
			table.find('.schema_column').addClass('selected');
		}

		getSQL();

	});

	schema_tables.find(".schema_column").click(function() {

		$("#schema_tables .selected2").removeClass('selected2');
		var table = $(this).parent('.schema_table');
		
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else {
			$(this).addClass('selected');
			table.addClass('selected');
		}

		getSQL();

	});

	query_textarea.html(default_query_text);

	adjustTables();
	$(window).resize(function() {
		adjustTables();
	});

	//clica em consultar

	execute_query.click(function() {

		executeQuery(current_query);

	});
   
  });


/*********** CUSTOM FUNCTIONS ************/

function executeQuery(query) {

	if(query.length < 5) {
		return;
	};

	var query_md5 = md5(query);

	if(typeof cached_queries[query_md5] !== 'undefined') {

		alert("Já existe! "+query_md5);

	} else {

		var pars = {
			query: query
		}

		$.post('lib/execute_query.php', pars, function(data) {

			data = $.parseJSON(data);

			if(data!="") {

				console.log(data);
				cached_queries[query_md5] = "teste";
			}

		}).error(function() {
			alert("Ocorreu um erro de conexão.");
		});

	}

}

function adjustTables() {
	$('#schema_tables .schema_table').wookmark({container: $("#schema_tables"), offset: 10});
}

function getSQL() {

	var ast = { FROM: [], SELECT: []};
	var schema_tables = $("#schema_tables");
	var query_spacer = $("#query_spacer");
	var query_wrapper = $("#query_wrapper");
	var query_textarea = $('#query');

	var selected_tables = schema_tables.find('.schema_table.selected');

	if(selected_tables.length === 0) {
		current_query = "";
		query_textarea.html(default_query_text);
		query_spacer.height(query_textarea.outerHeight() + 50);
		return;
	}

	selected_tables.each(function() {
		var table_name = $(this).attr('data-table');
		from_obj = {
			table: table_name,
			as: ""
		}
		ast.FROM.push(from_obj);

		// var num_total = $(this).find('.schema_column').length;
		// var num_selected = $(this).find('.schema_column.selected').length;

		// if(num_total === num_selected) {
		// 	ast.SELECT.push("`"+table_name+"`.*");
		// } else {
			$(this).find('.schema_column.selected').each(function() {
				var column_name = $(this).attr('data-column');
				ast.SELECT.push(table_name+"."+column_name);
			});
		// }

	});

	//console.log(ast);
	var sql_analysis = simpleSqlParser.ast2sql(ast);
	//console.log(sql_analysis);

	current_query = sql_analysis;
	query_textarea.html(current_query);
	query_spacer.height(query_textarea.outerHeight() + 50);

}

function analyseQuery() {
	var q = replaceAll("<br>", " ", current_query);
	q = replaceAll("<br/>", " ", q);
	q = replaceAll("&nbsp", " ", q);

	q = q.replace(/\s{2,}/g, ' ');
	q = q.replace(/`/g, '');

	$("#schema_tables .schema_table").css('opacity', 1);
	$("#schema_tables .selected").removeClass('selected');
	$("#schema_tables .selected2").removeClass('selected2');
	var analysis = simpleSqlParser.sql2ast(q);

	var table_as = [];

	if(typeof analysis.FROM !== "undefined" && analysis.FROM.length > 0) {
		for(var i in analysis.FROM) {
			var table = $.trim(analysis.FROM[i].table);
			table = table.split(" ");

			if(table.length > 0) {
				table_as[table[1]] = $.trim(table[0]);
			}

			table = $.trim(table[0]);
			$("#schema_tables [data-table='"+table+"']").addClass('selected');
		
			if(typeof analysis.SELECT !== "undefined" && analysis.SELECT.length > 0) {
				for(var i in analysis.SELECT) {
					var column = analysis.SELECT[i];
					column = column.split('.');
					if(column.length > 1) {
						var table2 = column[0]
						column = column[1];

						if(table2 != table && table_as[table2] != table) continue;
					}
					else column = column[0];

					if(column == "*") {
						$("#schema_tables [data-table='"+table+"'] [data-column]").addClass('selected');
						$("#schema_tables [data-table='"+table_as[table]+"'] [data-column]").addClass('selected');
					} else {
						$("#schema_tables [data-table='"+table+"'] [data-column='"+column+"']").addClass('selected');
						$("#schema_tables [data-table='"+table_as[table]+"'] [data-column='"+column+"']").addClass('selected');
					}
				}
			}

		}
	} else if(typeof analysis.SELECT !== "undefined" && analysis.SELECT.length > 0) {
		for(var i in analysis.SELECT) {
			var column = analysis.SELECT[i];
			column = column.split('.');
			if(column.length > 1) column = column[1];
			else column = column[0];

			if(column == "*") {
				$("#schema_tables [data-column]").addClass('selected');
			} else {
				$("#schema_tables  [data-column='"+column+"']").addClass('selected');
			}
		}
	}

	if(typeof analysis.WHERE !== "undefined") {
		$("#schema_tables .schema_table").css('opacity', 0.5);
		$("#schema_tables .schema_table.selected").css('opacity', 1);

		var terms = [];
		//if theres only a string
		if(typeof analysis.WHERE === "string") {
			terms.push(analysis.WHERE);
		}
		else if (typeof analysis.WHERE.left !== "undefined") {
			terms.push(analysis.WHERE.left);
			if(analysis.WHERE.right != "") terms.push(analysis.WHERE.right);
		}
		//if theres only one condition
		else if (typeof analysis.WHERE.terms !== "undefined"){
			var evaluate = analysis.WHERE.terms;
			while(evaluate.length > 0) {
				var term = evaluate.shift();
				if (typeof term === "string") {
					terms.push(term);
				}
				if (typeof term.left !== "undefined") {
					terms.push(term.left);
					if(term.right != "") terms.push(term.right);
				}
				else {
					for(var i in term.terms) {
						evaluate.push(term.terms[i]);
					}
				}

			}
		}

		for (i in terms) {

			var column = terms[i];
			column = column.split('.');
			if(column.length > 1) {
				table = column[0]
				column = column[1];
				$("#schema_tables .schema_table[data-table='"+table+"'].selected [data-column='"+column+"']").addClass("selected2");
				$("#schema_tables .schema_table[data-table='"+table_as[table]+"'].selected [data-column='"+column+"']").addClass("selected2");
			}
			else {
				column = column[0];
				$("#schema_tables .schema_table.selected [data-column='"+column+"']").addClass("selected2");
			}

		}

	}

	return analysis;
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

