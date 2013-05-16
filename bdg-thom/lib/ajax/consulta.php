<?php
ini_set("memory_limit", "512M");

if(!isset($_POST)) { die();}
session_start();

//carrega os arquivos
require_once '../classes/controllerClass.php';
require_once '../classes/tableClass.php';
require_once '../classes/svgClass.php';
require_once '../classes/viewClass.php';
require_once '../database/'.$_SESSION['sgbd'].'/connection.php';

//instancia as classes
$sobj  = new controllerClass();
$table = new tableClass();
$view  = new view();

//realiza a consulta
$resultado = $sobj->consultar();

//faz o desenho dos itens da consulta
$key = base64_encode($_POST['consulta']);
$var['mainlayer'] = $view->TableTime($table, $key, $resultado['time'], $resultado['res']);
if(geografico){
    $svg = new svgClass();
    $var['svgmap'] = $svg->draw($key, $resultado['mapa'], 600, 100, 5);
}
$var['sortable'] = $view->sortable($key, $_POST['consulta']);

//retorna o resultado
echo json_encode($var);

?>