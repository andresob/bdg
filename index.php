<?php 
session_start();

include('init.php');

?>

<!DOCTYPE html>
  <html lang="en">
	<head>
	<meta charset="utf-8">
	<title>GeoSQL</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Styles -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/styler.css" rel="stylesheet">
	<link href="css/specific.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/colorPicker.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Fav and touch icons -->
  </head>
  <body cz-shortcut-listen="true">

<div class="content clearfix">

	<div class="section menu">
		<?php include('menu.php'); ?>
	</div>	

		<div class="section main">
			<div id="query_wrapper" class="query-wrapper section-header">
				<div class="query-holder">
					<div id="query" placeholder="SELECT * FROM ..." contentEditable="true" class="query"></div>
				</div>
				<div class="query-btns">
					<button id="execute_query" title="Executar" rel="tooltip" class="btn btn-inverse btn-small"><i class="icon-play"></i></button>
				</div>
			</div>
			<div id="query_spacer" class="query-spacer">
			</div>

			<div id="tab_query" class="tab active">
				<?php include('tab_schema.php'); ?>
			</div>
			<div id="tab_map" class="tab">
				<?php include('tab_map.php'); ?>
			</div>
			<div id="tab_results" class="tab">
				<?php include('tab_results.php'); ?>
			</div>
		</div>
		<div class="section sidebar">
	
		<?php include('sidebar.php'); ?>
	
		</div>
	</div>

	<!-- Javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>

	<!-- JS:charts-->
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/jquery.plugins.js"></script>
	
	<script src="js/bootstrap.js"></script>
	
	<script src="js/sqlparser.js" type="text/javascript" charset="utf-8"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
	<script src="js/utils.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/custom2.js"></script>
  <script type="text/javascript">
    $("ul:first").dragsort();
  </script>
	

  </body>
</html>
