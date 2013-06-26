<?php

class sqlClass{

    private $erro = "";
    public function  __construct() {
        $this->bd_server   = bd_server;
        $this->bd_name     = bd_name;
        $this->bd_user     = bd_user;
        $this->bd_password = bd_password;
        $this->bd_dns      = bd_dns;
        $this->bd_port     = defined("bd_port")?bd_port:"";
        $this->ctime       = 0;
        $this->connect();
    }

    private function connect(){
       $port = ($this->bd_port != "")?";port=$this->bd_port":"";
       $dsn = $this->bd_dns.':host='.$this->bd_server.';dbname='.$this->bd_name.$port;
       $this->pdo = new PDO($dsn, $this->bd_user, $this->bd_password);
       $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
       if(!is_object($this->pdo)){
           die("Não foi possível instanciar o objeto do banco de dados!");
       }
       return true;
    }

    private function execute($query){
        $bd = $this->pdo;
        if(!is_object($bd)) throw new Exception(__CLASS__ . ": Erro na conexão do banco de dados");
        $q = $bd->prepare($query);
        //$q = $bd->prepare('fubá');
        $t1 = microtime();
        $q->execute();
        $t2 = microtime();
        $this->ctime = number_format($t2 - $t1, 4);
        if(stristr($query, "SELECT ") === FALSE) return true;
        return($q->fetchAll(PDO::FETCH_ASSOC));
    }

    //aqui será feita a consulta sql
    public function consultar($sql, $is_geo){
        try {
            if($is_geo){
                return $this->geoconsult($sql);
            }
            else {
                $result['time'] = $this->ctime;
                $result['res'] = $this->execute($sql);
                return $result;
            }
        }
        catch(Exception $e){
            $this->erro = "Query: $sql <br/><br/> Erro: ".$e->getMessage();
            return false;
        }
    }
    
    private function geoconsult($consulta){
        $result = array('res' => array(), 'mapa' => array());
        //Apenas executa esses passos se se tratar de um select(case insensitive)
        if (preg_match("/^[\t\n ]*SELECT/i", $consulta)) {
            $tabela_temporaria = "tabela_temporaria_" . rand(0, 100000);
            //(I) Cria uma tabela temporaria para receber o resultado da consulta do cliente.
            //Com essa tabela temporaria podemos verificar os tipos dos dados referentes as colunas
            //no passo posterior.
            $this->execute("DROP TABLE IF EXISTS $tabela_temporaria;");
            $this->execute("CREATE TEMPORARY TABLE $tabela_temporaria as ($consulta);");
            //(II) Retorna, se houver, o nome de UMA coluna geometrica de 'tabela_temporaria'.
            //A jogada da tabela temporaria esta aqui: Buscamos uma coluna do tipo geometry e sobre
            //ela poderemos trabalhar o SVG no passo posterior.
            $query = "SELECT   column_name
                      FROM     information_schema.columns, information_schema.tables
                      WHERE    information_schema.tables.table_name = information_schema.columns.table_name and
                      information_schema.tables.table_name = '$tabela_temporaria' and
                      information_schema.columns.udt_name = 'geometry'
                      LIMIT 1;";
            $result_parcial = $this->execute($query);
            //se $result_parcial tiver uma coluna como conteudo...
            if(is_array($result_parcial) && !empty ($result_parcial)){
                $result_parcial = array_shift($result_parcial);
                $result_parcial = $result_parcial['column_name'];
                //(III) Se II retornar o nome de uma coluna com dados geometricos, busca o SVG correspondente.
                //Como resultado teremos uma coluna com paths com distancias relativas. O que nos falta e' iterar
                //sobre as linhas dessa coluna colocando cada path dentro da tag <path>, o conjunto de <path> dentro
                //da tag <g> e o conjunto de <g> dentro de uma tag <svg>. As funcoes basicas estao no proximo passo.
                $t1 = microtime();
                $result['res']  = $this->execute("Select * from $tabela_temporaria;");
                $result['mapa'] = $this->execute("SELECT ST_ASSVG($result_parcial, 1) as svg FROM $tabela_temporaria;");
                $t2 = microtime();
                $this->ctime = number_format($t2 - $t1, 4);
        
                $this->execute("DROP TABLE IF EXISTS $tabela_temporaria;");
                
                
                //remove a coluna geometrica
                foreach($result['res'] as &$t){
                    if(array_key_exists($result_parcial, $t)) unset($t[$result_parcial]);
                }

            }else{
                $result['res'] = $this->execute($consulta);
                $result['mapa'] = array();
            }
        }else{
            $result['res'] = $this->execute($consulta);
            $result['mapa'] = array();
        }
        $result['time'] = $this->ctime;
        return $result;
    }
    
    public function getCTime(){
        return $this->ctime;
    }

    public function getErro(){
        return $this->erro;
    }
    
    public function getSchema(){
        $query = db_schema();
        try {$var = $this->execute($query);}
        catch(Exception $e){
            $this->erro = "Query: $query <br/><br/> Erro: ".$e->getMessage();
            return false;
        }
        $out = array();
        foreach($var as $v){
            $out[$v['tabela']][$v['coluna']] = @$v['ktype'];
        }
        return $out;
    }
}

?>
