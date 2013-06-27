<?php

require_once dirname(__FILE__).'/database/'.$_SESSION['sgbd'].'/connection.php';
$bd = new sqlClass();
$_SESSION['bd'] = $bd;

?>