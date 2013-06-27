<?php

session_start();

require_once 'autoload.php';

$possible_db = array('brasil');

if(!isset($_SESSION['sgbd']) || in_array($_SESSION['sgbd'], $possible_db) === false ){
   if(isset($_GET['sgbd'])) $_SESSION['sgbd'] = $_GET['sgbd'];
   else { $_SESSION['sgbd'] = "brasil"; }
}

$dir   = str_replace("\\", "/", dirname(__FILE__));
$doc   = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
$pj_name = str_replace(array($doc, "config", "//"), array("", "", "/"), $dir);

//nome do diretorio onde estara o projeto
if(!defined("PROJECT")) define('PROJECT', $pj_name);

$project = (PROJECT == "") ? "": PROJECT;
define('URL',  "http://".$_SERVER['SERVER_NAME'] . "$project/index.php");

include_once dirname(__FILE__).'/database/'.$_SESSION['sgbd'].'/connection.php';

$bd = new sqlClass();

$schema = $bd->getSchema();

$query = $_POST['query'];
$key = base64_encode($query);

if(!$query) {
	$result = "";
}
else {

	$var['query'] = $query;

	if(strpos($query,'geom') === false) {
		$geom = false;
	}
	else {
		$geom = true;
	}

	$result = $bd->consultar($query, $geom);

	if($result === false) $result['res'] = $bd->getErro();
	$result['time'] = $bd->getCTime();
	$var['res'] = $result['res'];

	if($geom === true) {
		$svg = new svgClass();
		$var['svgmap'] = $svg->draw($key, $result['mapa'], 800, -300, 15);
		$var['geom'] = true;
	} else {
		$var['geom'] = false;
	}
}



echo json_encode($var);

?>