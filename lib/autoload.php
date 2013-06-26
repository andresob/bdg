<?php

function __autoload($class_name) {
    if(file_exists(dirname(__FILE__). '/classes/'.$class_name . '.php'))
        require_once 'classes/'.$class_name . '.php';
}

?>