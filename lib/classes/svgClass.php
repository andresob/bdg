<?php

class svgClass{
    
    
    private $session = "colors";   
    private $default = array();   
    public function __construct() {
        
        //cria a classe de session
        if(!isset($_SESSION[$this->session])) $_SESSION[$this->session] = array();
        $this->default = array(
            'stroke'         => "black",
            'stroke_width'   => "0.005cm", 
            'stroke_opacity' => "1.0", 
            'fill'           => "none", 
            'fill_opacity'   => "0.0"
        );
        
    }
    
    public function setLineColor($id, $alpha, $color){
        $session = new sessionClass();
        $var = array('alpha' => $alpha, 'color' => $color);
        $session->compress($res);
        $_SESSION[$this->session][$id] = $var;
    }
    
    public function draw($id, $array, $x_translate = "0", $y_translate = "0", $scale = "1"){
        $var = $this->configureGroup($id, $array, $x_translate, $y_translate, $scale);
        return $var;
    }
    
    public function drawMultiple($array, $x_translate = "0", $y_translate = "0", $scale = "1"){
        $var = "";
        foreach($array as $id => $arr){
            //foreach($arrs as $arr)
                $var .= $this->configureGroup($id, $arr, $x_translate, $y_translate, $scale);
        }
        $var = $this->configureSVG($var);
        return $var;
    }
    
    //(VII) Esta funcao sera chamada para juntar em um SVG apenas um ou mais grupos gerados através do passo (VI). Esta funcao serve
    //tando para gerar um grafico quanto um merge de varios graficos, bastando que para isso passemos o grupo de cada grafico. 
    public function configureSVG($group_array = "", $width = "100%", $height='100%'){
            $result = "<svg id='svgmap' xmlns='http://www.w3.org/2000/svg' version='1.1'
                style='height: $height; width: $width;' preserveAspectRatio='xMaxYMax meet' >  ";
            if(is_array($group_array)){
                foreach($group_array as $group){
                        $append .= $group;
                }
            }else $result .= $group_array;
            $result .= "</svg>";	
            return $result;
    }

    //(VI) Esta funcao sera chamada apos todas as linhas retornadas no passo V terem sido colocadas no array $path_array
    public function configureGroup($id, $path_array, $x_translate = "0", $y_translate = "0", $scale = "1"){
            static $unique = 0;
            $unique++;
            $params = $this->getColor($id);
            extract($params);
            $result = "<g id='g_$unique' class='$id' stroke=\"$stroke\" stroke-width=\"$stroke_width\" 
                          stroke-opacity=\"$stroke_opacity\" fill=\"$fill\" fill-opacity=\"$fill_opacity\"
                          transform=\"translate($x_translate,$y_translate) scale($scale)\"> ";
            foreach($path_array as $path){
                if(count($path) > 1) echo "oh?";
                $path    = array_shift($path);
                $result .= $this->configurePath($path);
            }
            $result .= "</g>";
            return $result;
    }

    //(V) Esta funcao sera chamada para cada linha da coluna retornada no passo III, colocando os resultados em um array
    public function configurePath($path){
            static $id = 0;
            $id += 1;
            return "<path id=\"$id\" class='path' d=\"$path\" />";
    }

    public function setColor($id, $array){        
        //unset($_SESSION[$this->session]);
        //cria a variavel de sessao vazia
        $session = array();
        
        //carrega os valores já setados na session ou seta os valores default
        if(isset($_SESSION[$this->session][$id])) $session = $_SESSION[$this->session][$id];
        else $session = $this->default;
        
        foreach($array as $name => $value)
            if($value != "" && array_key_exists ($name, $this->default)) $session[$name] = $value;
            
        $_SESSION[$this->session][$id] = $session;
    }   
    
    public function getColor($id){
        return (
            array_key_exists($id, $_SESSION[$this->session])?
            $_SESSION[$this->session][$id]:
            $this->default
       );
    }
    
    public function getAllConfig(){
        $session = serialize($_SESSION[$this->session]);
        $session = urlencode($session);
        return $session;
    }
    
    public function restoreColors($colors){
        $colors  = urldecode($colors);
        $session = unserialize($colors);
        $_SESSION[$this->session] = $session;
    }

}

?>
