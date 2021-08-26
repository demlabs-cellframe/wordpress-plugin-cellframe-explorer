<?php
include_once  "Node_CLI.php";

class Node_CLI_CMD
{
private $command;
public function getCommand(){
    return $this->command;
}
public $func_parse;
private $static;
private $block;
public function allowCommandExecution(){
    $this->block = false;
}
public function preventCommandExecution(){
    $this->block = true;
}
public function isExec(){
    return !$this->block;
}
public function __construct()
{
    $this->static = " ";
    $this->preventCommandExecution();
}
protected function bind($cmd, $param, $parse_func){
    $this->command = $this->static.$cmd." ";
    foreach ($param as $arg){
        foreach ($arg as $element){
            $this->command .= " ".$element;
        }
    }
    $this->func_parse = $parse_func;
}
protected function checkData($data){
    $size_data = strlen($data);
    for ($i=0; $i < $size_data; $i++){
        if ($data[$i] == ' ' ||
            $data[$i] == '\\'||
            $data[$i] == '|' ||
            $data[$i] == '\/' ||
            $data[$i] == '&')
            return false;
    }
    return true;
}
}