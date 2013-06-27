<?php

require_once 'autoload.php';

$bd = $_SESSION['bd'];
$query = $_POST['query'];

echo addslashes($query);
exit;
$bd->consultar()

?>