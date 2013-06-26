<?php

require_once 'lib/autoload.php';
session_start();
if(!isset($_SESSION['sgbd'])){
   if(isset($_GET['sgbd'])) $_SESSION['sgbd'] = $_GET['sgbd'];
   else {echo "<meta http-equiv='refresh' content='0;URL=index.php' />";}
}

$dir   = str_replace("\\", "/", dirname(__FILE__));
$doc   = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
$pj_name = str_replace(array($doc, "config", "//"), array("", "", "/"), $dir);

//nome do diretorio onde estara o projeto
if(!defined("PROJECT")) define('PROJECT', $pj_name);

$project = (PROJECT == "") ? "": PROJECT;
define('URL',  "http://".$_SERVER['SERVER_NAME'] . "$project/praticando.php");

require_once 'lib/processa.php';

?>