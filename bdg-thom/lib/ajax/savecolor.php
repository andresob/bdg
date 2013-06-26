<?php
session_start();
require_once '../classes/svgClass.php';
$svg = new svgClass();
$svg->setColor($_POST['id'], $_POST);

?>