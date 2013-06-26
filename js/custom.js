$(document).ready(function () {

    $("[rel=tooltip]").tooltip();
    
    $('#tabbed-widget a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$('.table-data').dataTable();

   
  });