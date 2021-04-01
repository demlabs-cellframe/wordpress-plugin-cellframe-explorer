<?php
require_once "Node_CLI_CMD.php";

final class Node_CLI_CMD_Dag extends Node_CLI_CMD
{
private $net;
private $cmd;
private $chain;
public function __construct($net, $chain)
{
    parent::__construct();
    $this->cmd = "dag";
    if (parent::checkData($net) && parent::checkData($chain)){
        parent::allowCommandExecution();
    }
    $this->net = $net;
    $this->chain = $chain;
}
public function event_list(){
    $args = array(
        array("-net", $this->net),
        array("-chain", $this->chain),
        array("event"),
        array("list"),
        array("-from", "events")
    );
    parent::bind($this->cmd, $args, "event_list_parse");
}
public function event_list_parse($data){
    $hashes = array();
    if (strcmp($data[0], "Can't find network \"".$this->net."\"") == 0){
        $out  = (object)array(
            "net"=>$this->net,
            "chain"=>$this->chain,
            "count"=>0,
            "hashes"=>$hashes
        );
        return $out;
    }
    $count_el = explode(" ", $data[0])[2];
    for ($i=1;$i < count($data); $i++){
        $hash = explode(" ", $data[$i])[0];
        $hash = trim($hash, "\t:");
        $date = explode("=", $data[$i])[1];
        $dt = explode(" ", $date);
        if (count($dt) == 5)
            $date_str = "<span>" . $dt[0] . " " . $dt[1] . " " . $dt[2] . "</span> <span>" . $dt[3] . "</span> <span>" . $dt[4] . "</span>";
        else
            $date_str = "<span>" . $dt[0] . " " . $dt[1] . " " . $dt[3] . "</span> <span>" . $dt[4] . "</span> <span>" . $dt[5] . "</span>";
        $obj_hash = (object)array(
            "event" => $hash,
            "ts_created"=>$date_str
        );
        array_push($hashes, $obj_hash);
    }
    $out  = (object)array(
        "net"=>$this->net,
        "chain"=>$this->chain,
        "count"=>$count_el,
        "hashes"=>$hashes
    );
    return $out;
}
public function event_dump($hash_event){
    if (parent::isExec() && parent::checkData($hash_event)) {
        $args = array(
            array("-net", $this->net),
            array("-chain", $this->chain),
            array("event"),
            array("dump"),
            array("-event", $hash_event),
            array("-from", "events")
        );
        $this->bind("dag", $args, "event_dump_parse");
        parent::allowCommandExecution();
    } else {
        parent::preventCommandExecution();
    }
}
public function  event_dump_parse($data){
//    echo("<pre>");
//    var_dump($data);
//    echo("</pre><br/>");
    $ptr = 5;
    $links_count = trim(explode(":", $data[5])[2], "\t");
    $links = array();
    for ($j = 1; $j <= $links_count; $j++){
        $link = explode(":", $data[$ptr + $j])[1];
        $link = trim($link, "\t");
        array_push($links, $link);
    }
    $ptr = $ptr +1 + $links_count;
    $datum_size = trim(explode(":", $data[$ptr])[2], "\t");
    $ptr++;
    $datum_version = trim(explode("=", $data[$ptr])[1], "\t");
    $ptr++;
    $datum_type_id = trim(explode("=", $data[$ptr])[1], "\t");
    $ptr++;
    $datum_ts_create = trim(explode("=", $data[$ptr])[1], "\t");
    $ptr++;
    $datum_data_size = trim(explode("=", $data[$ptr])[1], "\t");
    $ptr++;
    $sings_count = trim(explode(":", $data[$ptr])[2], "\t");
    $event = trim(explode(" ", $data[0])[1], "\t");
    $n_event = substr($event, 0, strlen($event)-1);
    $ts_created = explode(":", $data[4]);
    $dump_data_obj = (object)(array(
        'event' => $n_event,
        'version' => explode(":", $data[1])[1],
        'cell_id' => explode(":", $data[2])[1],
        'chain_id' => explode(":", $data[3])[1],
        'ts_created' => $ts_created[1].":".$ts_created[2].":".$ts_created[3],
        'hashes' => $links,
        'datum_size' => $datum_size,
        'datum_version' => $datum_version,
        'datum_type_id' => $datum_type_id,
        'datum_ts_create' => $datum_ts_create,
        'datum_data_size' => $datum_data_size,
        'signsCount' => $sings_count
    ));
    $datum_declaration = NULL;
    if (count($data) == 19){
        $ptr += 2;
        $datum_declaration = (object)array(
            "tiker" => explode(":", $data[$ptr])[1],
            "size" => explode(":", $data[$ptr + 1])[1],
            "type" => explode(":", $data[$ptr + 2])[1],
            "sign_total" => explode(":", $data[$ptr + 3])[1],
            "sign_valid" => explode(":", $data[$ptr + 4])[1],
            "total_supply" => explode(":", $data[$ptr + 5])[1],
        );
    }
    $res = (object)array(
        "dump_data" => $dump_data_obj,
        "declaration" => $datum_declaration
    );
    return $res;
}
}