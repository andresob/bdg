var current_query = "";
var default_query_text = "Digite sua consulta SQL";
var cached_queries = [];
var colors = [], colors2 = [];

/*********** AFTER PAGE IS LOADED ************/

$(document).ready(function () {

    $("[rel='tooltip']").tooltip({placement: "bottom"});

    $(".autogrow").autogrow();
    
    $('#tabbed-widget a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$('.table-data').dataTable();

	query_textarea = $('#query');
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


	//cores nas consultas

	colors = ["f2f2f2", "78C0CF", "B8CF75", "E09498", "E6A587", "E8C680", "BBA493", "8AA1DF"];
	colors2 = ["CCCCCC", "4A8890", "818F49", "9B3F4C", "805C4B", "816E48", "7C6C63", "4968A8"];

	for(var i in colors) {
		colors[i] = colors[i].toLowerCase();
		colors2[i] = colors2[i].toLowerCase();
	}

	$('.queries_list').dragsort({ itemSelector: '.queries_list_item', dragSelector: '.queries_list_item', placeHolderTemplate: '<li class="queries_list_item"></li>' })
	$('.queries_list .color').colorPicker({colors: colors, transparency: true, showHexField: false}); 

	$("#loader").animate({
		opacity: 0
	}, function() {
		$("#loader").remove();
	});
   
  });


/*********** CUSTOM FUNCTIONS ************/

var saved_queries = [];
var queries_total = 0;

function newQuery(data, query_md5) {

	var color = queries_total % 8;
	var simp = data.query.substring(0,90)+"...";
	var item = '<li class="queries_list_item">';
	if(data.geom === true) item += '<span class="color"></span> ';
	item += '<span class="sql">'+simp+'</span></li>';

	$('.queries_list').prepend(item);

	var new_query = {
		id: query_md5,
		query: data.query,
		geom: data.geom,
		svg: data.svgmap,
		color: color,
		color1: '#'+colors[color],
		color2: '#'+colors2[color],
		query_item: $('.queries_list .queries_list_item').first(),
		execute: function() {
			plotResults(data.res);
		},
		remove: function() {
			this.query_item.remove();
			$("#map g#"+this.id).remove();
		},
		changeColor: function() {
			var c = this.query_item.find('.colorPicker-picker').attr('data-color').split("#")[1];
			var pos = $.inArray(c, colors);

			if(pos === -1) {
				console.log('Couldnt find '+c);
				return;
			}

			this.color = pos;
			this.color1 = '#'+colors[pos];
			this.color2 = '#'+colors2[pos];

			$("#map svg g#"+this.id+" path").each(function() {
				if($(this).attr("d")[$(this).attr("d").length -1] == 'z' || $(this).attr("d")[$(this).attr("d").length -1] == 'Z') {
					$(this)[0].style.fill = '#'+colors[pos];
				} else {
					$(this)[0].style.fillOpacity = 0;
				}
				$(this)[0].style.stroke = '#'+colors[pos];
			});
		}
	};

	new_query.query_item.find('.color').colorPicker({colors: colors, transparency: true, showHexField: false, onColorChange: function() {
		saved_queries[query_md5].changeColor();
	}}); 
	new_query.query_item.find('.color').css('background-color', new_query.color1+" !important");


	new_query.query_item.find('.sql').click(function() {
		query_textarea.html(new_query.query);
		query_textarea.focus();
	}); 

	//console.log(new_query);

	saved_queries[query_md5] = new_query;
	queries_total++;


}

function clearQueries() {

	for(var i in saved_queries) {
		saved_queries[i].remove();
	}

	saved_queries = [];
	queries_total = 0;
	$("#map").html('');

	$(".main #tab_results #table_results").remove();

	$(".main #tab_results #first_time").show();
	$(".main #tab_map #first_time").show();

	query_textarea.html('');
	query_textarea.blur();


}

function plotResults(json) {
	console.log(json);

	$(".main #tab_results #first_time").hide();
	$(".main #tab_results #table_results").remove();

	if(typeof json === "undefined" || json.length === 0 || json[0].length === 0) {
		$(".main #tab_results #no_results").show();
		switchTab('#tab_map');
	} else {
		$(".main #tab_results #no_results").hide();

		var tabela = '<div id="table_results" class="container-fluid"><div class="row-fluid"><div class="span12"><table class="table table-striped table-bordered"><thead><tr role="row"></tr></thead><tbody role="alert" aria-live="polite" aria-relevant="all"></tbody></table></div></div></div>';
		$(".main #tab_results").append(tabela);
		tabela = $(".main #tab_results #table_results table");
		var thead = tabela.find('thead tr');
		var tbody = tabela.find('tbody');

		//header
		for(var i in json[0]) {
			thead.append("<th>"+i+"</th>");
		}

		//body
		for(var i in json) {
			tbody.append("<tr>");
			for(var j in json[i]) {
				tbody.append("<td>"+json[i][j]+"</td>");
			}
			tbody.append("</tr>");
		}

		switchTab("#tab_results");

	}

}

function executeResults(data, query_md5) {

	if(typeof saved_queries[query_md5] !== "undefined") {
		saved_queries[query_md5].execute();
	} else {
		newQuery(data, query_md5);
		saved_queries[query_md5].execute();
	}

	initializeMap(data, query_md5);


}

function executeQuery(query) {

	var execute_query_btn = $("#execute_query");

	execute_query_btn.html('<i class="icon-spinner icon-spin"></i>');

	if(query.length < 5) {
		return;
	};

	var query_md5 = md5(query);

	if(typeof cached_queries[query_md5] !== 'undefined') {

		var data = cached_queries[query_md5];

		execute_query_btn.html('<i class="icon-play"></i>');

		executeResults(data, query_md5);

	} else {

		var pars = {
			query: query
		}

		$.post('lib/execute_query.php', pars, function(data) {

			data = $.parseJSON(data);

			if(data!="") {
				cached_queries[query_md5] = data;
				execute_query_btn.html('<i class="icon-play"></i>');

				executeResults(data, query_md5);
			}

		}).error(function() {
			alert("Ocorreu um erro de conexão.");
			execute_query_btn.html('<i class="icon-play"></i>');
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

function switchTab(new_tab) {

	$(".main .tab").removeClass('active');
	$(".navigation .current").removeClass('current');
	$(".main "+new_tab).addClass('active');
	$(".navigation li"+new_tab).addClass('current');

	adjustTables();

}

function initializeMap(data, md5) {

	$('.main #tab_map #first_time').hide();

	if(typeof data.svgmap === "undefined") return;

	var viewport = $("#map svg #viewport");

	if(viewport.length > 0) {
		viewport.append(data.svgmap);
		viewport.find('g').last().attr('id',md5);
		var m = $('#map').html();
		$('#map').html('');
		$('#map').html(m);
		$('#map svg').svgPan('viewport');

	}
	else {
		var g = '<svg xmlns="http://www.w3.org/2000/svg"><script xlink:href="js/SVGPan.js"/><g id="viewport" transform="matrix(0.9240345346828462,0,0,0.9240345346828462,427.4995349244402,399.0884233275848)">'+data.svgmap+'</g></svg>';
		$('#map').append(g);
		var viewport = $("#map svg #viewport");
		viewport.find('g').last().attr('id',md5);
	    $('#map svg').svgPan('viewport');
	}

	viewport.find('g').last().children('path').each(function() {
		if($(this).attr("d")[$(this).attr("d").length -1] != 'z' && $(this).attr("d")[$(this).attr("d").length -1] != 'Z') {
			$(this)[0].style.fillOpacity = 0;
		} else {
			$(this).click(function() {
				if($(this)[0].style.fillOpacity >= 0.8) $(this)[0].style.fillOpacity = 0.9
				else $(this)[0].style.fillOpacity = 'auto';
			});
		}
	});

	$("#print").show();

}

function parseSVG(s) {
    var div = document.createElementNS('http://www.w3.org/1999/xhtml', 'div');
    div.innerHTML= '<svg xmlns="http://www.w3.org/2000/svg">'+s+'</svg>';
    var frag= document.createDocumentFragment();
    while (div.firstChild.firstChild)
        frag.appendChild(div.firstChild.firstChild);
    return frag;
}

function modifySVGStroke() {
	var s = $("#map svg #viewport").attr("transform").split("(")[1].split(",")[0];
	var num = 0.08/s;

	num = (num > 0.1) ? 0.1 : num;

	$("#map svg path").each(function() {
		$(this)[0].style.strokeWidth = num;
	});
}
