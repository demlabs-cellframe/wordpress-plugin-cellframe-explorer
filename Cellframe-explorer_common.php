<?php

class CE_Common{
    private $base_url;
    public static function getHtmlSelect($options, $default, $class, $name, $js_onclick){
        $str = "<select class='".$class."' name='".$name."' onclick='".$js_onclick."'>";
        foreach ($options as $element){
            if ($element == $default){
                $str .= "<option value='".$element."' selected='true'>".$element."</option>";
            } else {
                $str .= "<option value='".$element."'>".$element."</option>";
            }
        }
        $str .= "</select>";
        return $str;
    }

    public static function strToArray($str, $spacer){
        $data = array();
        $str_len = strlen($str);
        $d = "";
        for ($i=0; $i < $str_len; $i++){
            if ($str[$i]==$spacer){
                array_push($data, $d);
                $d = "";
            } else {
                $d .= $str[$i];
            }
        }
        array_push($data, $d);
        return $data;
    }
}

class Cellframe_explorer_generation{
}

function nets_html($nets_str, $default_net){
    //
}
function chains_html($chains_str, $default_chain){
    $chains = array();
    $chains_str_len = strlen($chains_str);
    $d = "";
    for ($i=0; $i < $chains_str_len; $i++){
        if ($chains_str[$i]== ','){
            array_push($chains, $d);
            $d = "";
        } else {
            $d .= $chains_str[$i];
        }
    }
    array_push($chains, $d);
    $str = "<select>";
    for ($i = 0; $i < count($chains); $i++){}
    return $chains;
}