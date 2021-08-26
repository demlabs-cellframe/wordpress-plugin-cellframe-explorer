<?php


class Node_CLI_CMD_Wallet extends Node_CLI_CMD
{
    private $cmd;
    public function __construct()
    {
        parent::__construct();
        $this->cmd = "wallet";
        parent::allowCommandExecution();
    }
    public function info($addr){
        if (parent::isExec() && parent::checkData($addr)) {
            $args = array(
                array("info", ""),
                array("-addr", $addr)
            );
            parent::bind($this->cmd, $args, "parse_wallet_info_addr_result");
            parent::allowCommandExecution();
        } else {
            parent::preventCommandExecution();
        }
    }
    public function parse_wallet_info_addr_result($data){
        $c_data_el = count($data);
        $balance = array();
        for ($i=3; $i < $c_data_el; $i++){
            $el_balance = explode(" ", $data[$i]);
            $el_balance[0][0] = ' ';
            $datoshi = trim($el_balance[1], '()');
            array_push($balance, ((object)array(
                "coins" => $el_balance[0],
                "datoshi" => $datoshi,
                "token" => $el_balance[2]
            )));
        }
        $ret = (object)array(
            "addr" => explode(":", $data[0])[1],
            "network" => explode(":", $data[1])[1],
            "balance" => $balance
        );
        return $ret;
    }

}