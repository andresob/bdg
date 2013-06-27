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
$query = addslashes($query);

if(!$query) {
	echo json_encode("");
}
else {

	$result = $bd->consultar($query, true);
	if($result === false) $result['res'] = $bd->getErro();
	$result['time'] = $bd->getCTime();

	echo json_encode($result);

}

?>