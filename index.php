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
			<div class="query-wrapper section-header">
				<textarea placeholder="SELECT * FROM ..." class="query autogrow"></textarea>
			</div>

			<div class="tab active view_query">
				<?php include('tab_schema.php'); ?>
			</div>
			<div class="tab view_query">
				<?php include('tab_map.php'); ?>
			</div>
			<div class="tab view_query">
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
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
	<script src="js/custom.js"></script>
	

  </body>
</html>