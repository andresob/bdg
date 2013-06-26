<?php

session_start();
require_once '../classes/controllerClass.php';
require_once '../database/'.$_SESSION['sgbd'].'/connection.php';

$controller  = new controllerClass();
$controller->apagaconsulta();

echo json_encode(array('success' => true));
?>