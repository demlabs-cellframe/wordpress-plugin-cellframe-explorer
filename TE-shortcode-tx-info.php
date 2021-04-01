<?php

include_once ("Node_CLI_CMD_Ledger.php");
include_once ("Node_CLI.php");

add_shortcode('TE_TX_INFO', 'TE_tx_info');

function TE_tx_info($atts){
	wp_enqueue_script("CETxInfo",
		plugin_dir_url(__FILE__). "js/Transaction-explorer.js");
	if (!isset($_GET['net']) || !isset($_GET['hash'])) {
		return "<div class='error'>Link do not have address</div>";
	} else {
		$ledger = new Node_CLI_CMD_Ledger($_GET['net']);
		$ledger->ledger_tx_info($_GET['hash']);
		$node = new Node_CLI();
                $res = $node->exec($ledger);
		$res_data = $res->getData();
		$str = "<div id='TX'></div><script type='text/javascript'>
                document.addEventListener('DOMContentLoaded', function(){
		    var data = JSON.parse('".json_encode($res_data)."');
                    document.getElementById(\"TX\").innerHTML = TxInfoFromJSONToHTML(data, \"".get_site_url()."/".$atts['url_page_addr_info']."\");
                });
		</script>";
                return $str;
	}
}
