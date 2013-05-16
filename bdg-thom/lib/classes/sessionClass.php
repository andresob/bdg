<?php

class sessionClass{
    
    private $session = 'consulta';
    public function __construct() {
        if(!isset($_SESSION[$this->session]))  $_SESSION[$this->session] = array();
    }
    
    public function insert($session_name, $dados){
        
        //comprime o resultado
        $dados = $this->compress($dados);
        
        //se resultado não foi registrado, registra
        if(!array_key_exists($session_name , $_SESSION[$this->session])){
            $_SESSION[$this->session][$session_name] = $dados;
        }
    }
    
    public function delete($session_name){
        if(isset($_SESSION[$this->session][$session_name]))
            unset($_SESSION[$this->session][$session_name]);
    }
    
    public function select($session_name = ""){
        
        //se estiver procurando uma session especifica
        if($session_name != ""){
            if(!array_key_exists($session_name, $_SESSION[$this->session])) return array();
            return $this->uncompress($_SESSION[$this->session][$session_name]);
        }
        
        //se quiser todas as sessions
        $temp = array_reverse($_SESSION[$this->session]);
        $out  = array();
        foreach ($temp as $session_name => $arr){
            $out[$session_name] = $this->uncompress($arr);
        }
        return $out;
    }
        
    public function toFirst($session_name){
        if(array_key_exists($session_name, $_SESSION[$this->session])) {
            $var = $_SESSION[$this->session][$session_name];
            unset ($_SESSION[$this->session][$session_name]);
            $_SESSION[$this->session][$session_name] = $var; 
        }
    }
    
    public function getFirst(){
        $var   = end($_SESSION[$this->session]);
        $keys  = array_keys($_SESSION[$this->session]);
        $keys  = end($keys);
        $array = $this->uncompress($var);
        if(empty ($array)) return $array;
        $map[$keys] = $array;
        return $map;
    }
    
    public function compress($res){
        if($res == "") return "";
        $res = serialize($res);
        $res = gzcompress($res);
        return $res;
    }
    
    public function uncompress($res){
        if($res == "") return "";
        $res = gzuncompress($res);
        $res = unserialize($res);
        return $res;
    }
}

?>
