<?php
ini_set("memory_limit", "512M");
require_once 'sqlClass.php';
require_once 'svgClass.php';
require_once 'consultaClass.php';
require_once 'sessionClass.php';

class controllerClass{
    
    public function __construct() {
        //cria a variavel de sessao (responsável por guardar as consultas
        $this->con = new consultaClass();
        $this->sql = new sqlClass();
        $this->svg = new svgClass();
 
    }
    
    //faz uma nova consulta
    public function consultar(){
        $consulta = isset($_POST['consulta'])?$_POST['consulta']:"";
        if($consulta == "") return;
        return $this->con->consultar($consulta);
    }
    
    public function merge(){
        $consult = explode(";", $_GET['consult']);
        array_pop($consult);
        
        $map = array();
        foreach($consult as $cons){
            $temp       = $this->con->recuperar($cons);
            $map[$cons] = $temp['mapa'];
        }
        
        $var = $this->svg->drawMultiple($map, 700, 100, 9);
        die($var);
    }
    
    //apaga uma consulta caso ela exista
    public function apagaconsulta(){
        if(isset($_REQUEST['consulta'])){
            $this->con->apagar($_REQUEST['consulta']);
        }
    }
       
    public function getTags(){
        return $_SESSION['tags'];
    }
    
    public function getResult($consulta = ""){
        if($consulta == "") return $this->con->getAll();
        return $this->con->recuperar($consulta);      
    }
    
    public function getlink(){
        $cons = $v = $color = "";
        $sql  = $this->con->recuperar('');
        foreach($sql as $var => $sq){
            $cons .= $v .$var;
            $v = ";";
        }
        
        $color = $this->svg->getAllConfig();
        $sgbd = $_SESSION['sgbd'];
        $link = "<div style='width: 600px;'>".URL ."/praticando.php?sgbd=$sgbd&action=consultbylink&multicons=$cons&color=$color</div>";
        die($link);
    }
    
    public function consultbylink(){
        $_SESSION['consulta'] = array();
        $_SESSION['consres'] = array();
        if(isset($_GET['multicons'])) {
            $consultas = explode(";", $_GET['multicons']);
            foreach($consultas as $cons) {
                $_POST['consulta'] = base64_decode($cons);
                $this->consultar($cons);
            }
        }
        if(isset($_GET['color'])) {
            $this->svg->restoreColors($_GET['color']);
        }
        $this->redirect(URL);
    }
    
    public function genSchema(){
        if(isset($_SESSION['schema'])) return $_SESSION['schema'];        
        $schema = $this->sql->getSchema();
        $html   = "";
        if(is_array($schema)){
            foreach($schema as $tablename => $sc){
                $tags[] = "'$tablename'";
                $html .= "$tablename(";
                $v = "";
                    foreach($sc as $col => $key){
                        $col   = ($key == "PRI")?"<u>$col</u>":$col;
                        $html .= "$v$col";
                        $v = ", ";
                        $tags[] = "'$col'";
                    }
                $html .= ")<br/><br/>";
            }
        }else{ $html = $this->sql->getErro(); }
        $_SESSION['schema'] = $html;
        $tags[] = "'SELECT'"; $tags[] = "'FROM'"; $tags[] = "'WHERE'";
        $tags[] = "'LIMIT'"; $tags[] = "'OFSET'"; $tags[] = "'ORDER BY'";
        $tags[] = "'count(*)'"; $tags[] = "'SUM()'"; 
        $_SESSION['tags'] = implode(', ', $tags);
        return $_SESSION['schema'];
    }
    
    private function redirect($url){
        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
              <meta http-equiv='refresh' content='0;URL=$url' />";
    }
}

?>
