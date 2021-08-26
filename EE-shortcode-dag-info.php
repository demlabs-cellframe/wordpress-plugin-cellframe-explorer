<?php

include_once ("Node_CLI_CMD_Dag.php");
include_once ("Node_CLI.php");

add_shortcode("EE_DAG_INFO", "EE_dag_info");

function EE_dag_info($atts){
    wp_enqueue_script("CEEEInfo",
        plugin_dir_url(__FILE__). "js/Event-explorer.js");
    if (isset($_GET['hash']) && isset($_GET['chain']) && isset($_GET['net'])){
        $dag = new Node_CLI_CMD_Dag($_GET['net'], $_GET['chain']);
        $dag->event_dump($_GET['hash']);
        if(!$dag->isExec())
            return "<div class='error'>Link have invalid data</div>";
        $node = new Node_CLI();
        $result = $node->exec($dag);
        $str = "<a href='".get_site_url().$atts['base_url_page']."?net=".$_GET['net']."&chain=".$_GET['chain']."' class='btn'>To table with other event</a><div id='DagTable'></div>";
        $str .= "<script type='text/javascript'>";
        $res_data = $result->getData();
        $str .= "
            var result = JSON.parse('".json_encode($res_data)."');
            var net_selected = \"".$_GET['net']."\";
            var chain_selected = \"".$_GET['chain']."\";
            document.addEventListener('DOMContentLoaded', function(){
                var ret = EEInfoFromJSONToHTML(result, \"".get_page_link()."?\");
                document.getElementById(\"DagTable\").innerHTML= ret;
            });
            </script>
            ";
        return $str;
    }
    return "<div class='error'>Link do not have data</div>";
}
