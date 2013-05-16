<?php
require_once dirname(__FILE__).'/classes/controllerClass.php';
require_once dirname(__FILE__).'/database/'.$_SESSION['sgbd'].'/connection.php';
require_once dirname(__FILE__).'/classes/viewClass.php';

$vobj = new view();
$sobj = new controllerClass();

$schema = $sobj->genSchema();
$action = isset($_GET['action'])?$_GET['action']: "";
if(method_exists($sobj, $action)){
    $sobj->$action();
}

$resultado = $sobj->getResult('');
$tags      = $sobj->getTags();
$resultado = $vobj->prepare($resultado);
extract($resultado);

?>