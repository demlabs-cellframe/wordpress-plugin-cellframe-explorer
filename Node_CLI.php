<?php
include_once("Node_CLI_CMD.php");

class Node_CLI
{
private  $dir_node;
public function __construct($dir_node = "/opt/cellframe-node/bin/cellframe-node-cli"){
    $this->dir_node = $dir_node;
}
public function exec($Node_CLI_CMD){
    if (is_object($Node_CLI_CMD)){
        if (!$Node_CLI_CMD->isExec()){
            return new Node_CLI_RESULT(NULL, 2);
        }
        $cmd = $this->dir_node.$Node_CLI_CMD->getCommand();
        exec($cmd, $result);
        if(count($result) == 2){
            if (strcmp($result[1], "Can't connect to cellframe-node") == 0){
                return new Node_CLI_RESULT(NULL, 1);
            }
        }
        $res_preparse = $this->pre_parser($result);
        $parse = $Node_CLI_CMD->func_parse;
        $res_parse = $Node_CLI_CMD->$parse($res_preparse);
        return (new Node_CLI_RESULT($res_parse, 0));
    }
}
private function pre_parser($in){
    $out = array();
    foreach ($in as $element){
        if ($element == '')
            continue;
        array_push($out, $element);
    }
    return $out;
}
}
class Node_CLI_RESULT{
    private $data;
    public function getData()
    {
        return $this->data;
    }
    private $status;
    public function getStatus()
    {
        return $this->status;
    }
    public function __construct($data, $status)
    {
        $this->data = $data;
        $stat = new Node_CLI_RESULT_Status($status);
        $this->status = $stat->getStatus();
    }
}
class Node_CLI_RESULT_Status{
    private $status;
    public function getStatus(){
        return $this->status;
    }
    public function __construct($status)
    {
        $statuses = array(
            (object)array(
                "code"=>0,
                "message"=>"OK"
            ),
            (object)array(
                "code"=>1,
                "message"=>"NoConnect"
            ),
            (object)array(
                "code"=>2,
                "message"=>"AllowCommand"
            ),
            (object)array(
                "code"=>3,
                "message"=>"Other False"
            ));
        $this->status = $statuses[3];
        if ($status == 0 || $status == "OK")
            $this->status = $statuses[0];
        if ($status == 1 || $status == "NoConnect")
            $this->status = $statuses[1];
        if ($status == 2 || $status == "AllowCommand")
            $this->status = $statuses[2];
    }
}