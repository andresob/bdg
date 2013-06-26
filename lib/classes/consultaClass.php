<?php

class consultaClass{
    
    public function __construct() {
        $this->sql = new sqlClass();
        $this->ses = new sessionClass();
    }

    public function consultar($consulta){
        
        //se a nova consulta está vazia, então retorna
        if($consulta == "") return;      
        
        //verifica se a consulta já existe
        $cons   = base64_encode($consulta);
        $result = $this->ses->select($cons);
        if(!empty ($result)) return $result;
        
        //faz a nova consult
        $result = $this->sql->consultar($consulta, geografico);
        if($result === false) $result['res'] = $this->sql->getErro();
        $result['time'] = $this->sql->getCTime();
        
        //grava a nova consulta
        $this->ses->insert($cons, $result);
        return $this->recuperar($cons);
        
    }
    
    public function getAll(){
        return $this->ses->select("");
    }
    
    //se foi feita uma consulta que estava na lista, coloca esta na primeira posicao
    public function recuperar($consulta){
        $this->ses->toFirst($consulta);
        return $this->ses->select($consulta);
    }
    
    public function apagar($consulta){
        $this->ses->delete($consulta);
    }
    
    public function getFirst(){
        $this->ses->getFirst();
    }
    
}

?>