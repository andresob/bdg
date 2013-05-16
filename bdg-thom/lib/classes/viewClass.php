<?php

class view{
    
    public function prepare($result){
        
        $resultado = $map = $layer = $tmp_res = "";
        $out = array();
        $svg = new svgClass();
        if(!empty ($result)){
            
            $table = new tableClass();
            foreach($result as $id => $array){
                $resultado[] = $this->TableTime($table, $id, $array['time'], $array['res']);
                $layer     = base64_decode($id);
                $layers[]  = $layer;

                if(geografico)
                    $map .= $svg->draw($id, $array['mapa'], 600, 100, 5);
            }
            //$resultado = array_reverse($resultado);
            
            $out['result'] = implode(" ",$resultado);
            $out['first']  = $layer;
            $out['layers'] = $this->drawLayers($layers);
        }
        
        if(geografico)
            $out['map'] = $svg->configureSVG($map);
        
        return $out;
    }
    
    public function TableTime($table, $id, $time, $res){
        $tmp = "<div class='table-container $id'>Consulta realizada em: $time s <hr/>";
        return $tmp . $table->draw($res, $id) . "</div>";
    }
    
    public function drawLayers($layers){
        
        if(!is_array($layers)) return "";
        $var = "";
        foreach($layers as $layer){
             $key  = base64_encode($layer);
             $var .= $this->sortable($key, $layer);
        }
        return $var;
    }
    
    public function sortable($key, $consulta){                
        $var = "
        <li id='$key' class='layer border'>
            <a href='#' class='selecionar'>
                <div class='item bg bg-hover'>". nl2br($consulta)."</div>
            </a>
            <div class='acoes'>
                <a href='#' class='recupera_consulta action'><img src='img/btn_editar.png'/></a>";
            if(geografico){
                $var .= "<a class='action colorSelector bgcolor' id='_$key'></a>";
                $var .= "<a class='action colorSelector licolor' id='_$key'></a>";
            }    
            $var .= "<a href='#' class='apaga_consulta action'><img src='img/btn_excluir.png'/></a>
            </div>
        </li>
        ";
        return $var;
    }
}

?>