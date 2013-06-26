<?php

class tableClass {
    
    public function draw($center, $id = ""){

        if(!is_array($center) || empty ($center)) return;
        $var = end($center);
        $header = array();
        foreach($var as $name => $value)  $header[] = $name;
        $footer = $header;
        
        $id = ($id == "")? "id='table'": "id='$id'";
        $this->flush = "<div class='tabela'>";
            $this->flush .= "<table $id class='tablesorter'>";
                $this->flush .= "<thead>";
                    $this->headers($header);
                $this->flush .= "</thead>";
                $this->flush .= "<tbody>";
                    $this->imprime_centro($center, $header);
                $this->flush .= "</tbody>";
                $this->flush .= "<tfoot>";
                    $this->headers($footer);
                $this->flush .= "</tfoot>";
            $this->flush .= "</table>";
        $this->flush .= "</div>";
        
        return $this->flush;
    }
   
    private function headers($array){
        $this->flush .=  "<tr>";
        foreach($array as $v){
            $this->flush .=  "<td class='$v'>".ucfirst($v)."</td>";
        }
        $this->flush .=  "</tr>";
    }

    private function imprime_centro($array, $header){

        $class = "";
        foreach($array as $name => $value){

            if(is_array($value)){
                $class = ($class == "")? "dif": "";
                $this->flush .=  "<tr class='$class'>";
                foreach($header as $v){
                    if(array_key_exists($v, $value)){
                        $key = $value[$v];
                        $style = "";
                        if(is_numeric(str_replace(array(","), "", $key))){
                            $style = "style='text-align:right'";
                        }
                        $this->flush .=  "<td class='$v' $style>$key</td>";
                    }
                }
                $this->flush .=  "</tr>";
            }
        }
        
    }

}

?>
