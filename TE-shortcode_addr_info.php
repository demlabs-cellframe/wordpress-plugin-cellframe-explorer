<?php

include_once ("Node_CLI_CMD_Wallet.php");
include_once ("Node_CLI.php");

add_shortcode("TE_ADDR_INFO", "TE_addr_info");

function TE_addr_info($atts){
    if (!isset($_GET['addr'])){
        return "<div class='error'>Link do not have address</div>";
    } else {
        $wallet = new Node_CLI_CMD_Wallet();
        $wallet->info($_GET['addr']);
        if(!$wallet->isExec())
            return "<div class='error'>Link have invalid data</div>";
        $node = new Node_CLI();
        $res = $node->exec($wallet);
        $res_data = $res->getData();
        $str = " <div id='ADDR'></div>
        <script type='text/javascript'>
            var data = JSON.parse('".json_encode($res_data)."');
            var d_html = \"<div class='addr_info_container'><div class='addr_info_title'>Addr</div><div class='addr_info_data'>\"+data.addr+\"</div></div>\" +
            \"<div class='addr_info_container'><div class='addr_info_title'>Network</div><div class='addr_info_data'>\"+data.network+\"</div></div>\" +
            \"<table cellpadding='5' cellspacing='0' border='0' style='padding-left:50px;'><thead><th>Coins</th><th>Datoshi</th><th>Token</th></thead><tbody>\";
            data.balance.forEach(function(item){
                d_html += \"<tr><td>\"+item.coins+\"</td><td>\"+item.datoshi+\"</td><td>\"+item.token+\"</td></tr>\";
            });
            d_html += \"</tbody></table>\";
            document.getElementById(\"ADDR\").innerHTML = d_html;
        </script>
        ";
        return $str;
    }
}
