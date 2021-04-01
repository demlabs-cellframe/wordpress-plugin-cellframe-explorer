<?php
require_once "Node_CLI_CMD.php";


final class Node_CLI_CMD_Ledger extends Node_CLI_CMD
{
    private $net;
    private $chain;
    private $cmd;
    public function __construct($net, $chain = NULL)
    {
        parent::__construct();
        $this->cmd = "ledger";
        if ($chain != NULL){
            if (parent::checkData($net) && parent::checkData($chain)){
                parent::allowCommandExecution();
            }
            $this->net = $net;
            $this->chain = $chain;
        } else {
            if (parent::checkData($net)){
                parent::allowCommandExecution();
            }
            $this->net = $net;
        }
    }
    public function ledger_tx_all(){
        if ($this->chain != NULL){
            $args = array(
                array("tx", "-all"),
                array("-chain", $this->chain),
                array("-net", $this->net),
                array("-H", "hex")
            );
        } else {
            $args = array(
                array("tx", "-all"),
                array("-net", $this->net),
                array("-H", "hex")
            );
        }
        parent::bind($this->cmd, $args, "ledger_tx_all_parse");
    }
    public function ledger_tx_all_parse($data){
        $res_out = "<>";
        $c_elements = count($data);
        $ledger = array();
        for ($i = 0; $i < $c_elements; $i++){
            $nts = explode(":", $data[$i]);
            if($nts[0] == "transaction" && count($nts) == 3){
                $i_str = 2;
                $el = trim($data[$i + $i_str], "\t: ");
                $items = array();
                //while ($i_str >= 2)
                do{
                    $str_tmp = $i + $i_str;
                    switch($el){
                        case "IN":
                            $tx_prev_hash = explode(":", $data[$str_tmp+1])[1];
                            $tx_out_prev_idx = explode(":", $data[$str_tmp+2])[1];
                            array_push($items, (object)array(
                                "TYPE" => "IN",
                                "tx_prev_hash" => $tx_prev_hash,
                                "tx_out_prev_idx" => $tx_out_prev_idx));
                           $i_str += 2;
                           break;
                        case "OUT":
                    	    $value = explode(":", $data[$str_tmp+1])[1];
                    	    $address = explode(":", $data[$str_tmp+2])[1];
                    	    array_push($items, (object)array(
                                "TYPE" => "OUT",
                                "value" => $value,
                                "address" => $address
                            ));
                            $i_str += 2;
                            break;
                        case "TOKEN":
                            $ticker = explode(":", $data[$str_tmp+1])[1];
                            $token_emission_hash = explode(":", $data[$str_tmp+2])[1];
                            $token_emission_chain_id = explode(":", $data[$str_tmp+3])[1];
                            array_push($items, (object)array(
                                "TYPE" => "TOKEN",
                                "ticker" => $ticker,
                                "token_emission_hash" => $token_emission_hash,
                                "token_emission_chain_id" => $token_emission_chain_id
                            ));
                            $i_str += 3;
                            break;
                        case "SIG":
                            $sig_size = explode(":", $data[$str_tmp+1])[1];
                            $sign_type = explode(":", $data[$str_tmp+3])[1];
                            $sign_pub_key_hash = explode(":", $data[$str_tmp+4])[1];
                            $sign_pub_key_size = explode(":", $data[$str_tmp+5])[1];
                            $sign_size = explode(":", $data[$str_tmp+6])[1];
                            array_push($items, (object)array(
                                "TYPE" => "SIG",
                                "size" => $sig_size,
                                "Signature" => (object)array(
                                    "type" => $sign_type,
                                    "pubKey" => (object)array(
                                        "hash" => $sign_pub_key_hash,
                                        "size" => $sign_pub_key_size
                                    ),
                                    "size" => $sign_size
                                    )
                            ));
                            $i_str += 6;
                            break;
                        default:
                            $i += $i_str;
                            $i_str = 0;
                            break;
                    }
                    $i_str += 1;
                    $el = trim($data[$i + $i_str ], "\t: ");
                }while($i_str > 2);
                $i_str = 2;
                array_push($ledger, (object)array(
                    "hash" => $nts[2],
                    "items" => $items
                ));
            }
        }
        return $ledger;
    }
    public function ledger_tx_info($hash){
        if (parent::checkData($hash) && parent::isExec()){
            parent::allowCommandExecution();
            $args = array(
                array("tx", "info"),
                array("-hash", $hash),
                array("-net", $this->net)
            );
            parent::bind($this->cmd, $args, "ledger_tx_info_parse");
        } else {
            parent::preventCommandExecution();
        }
    }
    public function ledger_tx_info_parse($data){
        $count_str = count($data);
        $tx_hash = explode(":", $data[0])[2];
        $items = array();
        for($i = 2; $i < $count_str; $i++){
            $item_type = trim($data[$i], "\t: ");
            switch($item_type){
                case "IN":
                    $tx_prev_hash = explode(":", $data[$i+1])[1];
                    $tx_out_prev_idx = explode(":", $data[$i+2])[1];
                    array_push($items, (object)array(
                        "TYPE" => "IN",
                        "tx_prev_hash" => $tx_prev_hash,
                        "tx_out_prev_idx" => $tx_out_prev_idx));
                    $i += 2;
                    break;
                case "OUT":
                    $value = explode(":", $data[$i+1])[1];
                    $address = explode(":", $data[$i+2])[1];
                    array_push($items, (object)array(
                        "TYPE" => "OUT",
                        "value" => $value,
                        "address" => $address
                    ));
                    $i += 2;
                    break;
                case "TOKEN":
                    $ticker = explode(":", $data[$i+1])[1];
                    $token_emission_hash = explode(":", $data[$i+2])[1];
                    $token_emission_chain_id = explode(":", $data[$i+3])[1];
                    array_push($items, (object)array(
                        "TYPE" => "TOKEN",
                        "ticker" => $ticker,
                        "token_emission_hash" => $token_emission_hash,
                        "token_emission_chain_id" => $token_emission_chain_id
                    ));
                    $i += 3;
                    break;
                case "SIG":
                    $sig_size = explode(":", $data[$i+1])[1];
                    $sign_type = explode(":", $data[$i+3])[1];
                    $sign_pub_key_hash = explode(":", $data[$i+4])[1];
                    $sign_pub_key_size = explode(":", $data[$i+5])[1];
                    $sign_size = explode(":", $data[$i+6])[1];
                    array_push($items, (object)array(
                        "TYPE" => "SIG",
                        "size" => $sig_size,
                        "Signature" => (object)array(
                            "type" => $sign_type,
                            "pubKey" => (object)array(
                                "hash" => $sign_pub_key_hash,
                                "size" => $sign_pub_key_size
                            ),
                            "size" => $sign_size
                        )
                    ));
                    $i += 6;
                    break;
                default:
                    break;
            }
        }
        $ret = (object)array(
            "hash" => $tx_hash,
            "items" => $items
        );
        return $ret;
    }

}
