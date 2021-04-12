<?php
include_once ("Node_CLI_CMD_Ledger.php");
include_once ("Cellframe-explorer_common.php");
include_once ("TE-rest-api.php");
include_once ("TE-shortcode_addr_info.php");
include_once ("TE-shortcode-tx-info.php");

add_shortcode("TE_HISTORY", "TE_history");

function TE_history($atts){
    wp_enqueue_script('jquery');
    wp_enqueue_style("datatables",
        plugin_dir_url(__FILE__) . "DataTables/css/dataTables.bootstrap.min.css");
    wp_enqueue_script("datatables",
        plugin_dir_url(__FILE__) . "DataTables/js/jquery.dataTables.js", ['jquery'],
        false, false);
    wp_enqueue_script("CETxInfo",
        plugin_dir_url(__FILE__). "js/Transaction-explorer.js");
    if (!isset($_GET['net']))
        $net = $atts['default_net'];
    else
        $net = $_GET['net'];
    $nets = CE_Common::strToArray($atts['nets'], ",");
    $rest_url = get_rest_url();
    $rest_url_list = $rest_url."transactionExplorer/v1/list/";
    $select_net = CE_Common::getHtmlSelect($nets, $net, "te_list-nets", "nets", "
        te_net_selected = jQuery(\".te_list-nets option:selected\").val();
        jQuery.ajax({
            url: \"".$rest_url_list."\"+te_net_selected,
            dataType: \"json\",
            success: function(data){
                console.log(data);
                te_table.clear();
                //data_parse = JSON.parse(data);
                jQuery(\".te_count_event\").text(data.length);
                if (data.length > 0)
                    te_table.rows.add(data);
                te_table.draw();
            }
        });
    ");
    $tranaction = new Node_CLI_CMD_Ledger($net);
    $tranaction->ledger_tx_all();
    $node = new Node_CLI();
    $result = $node->exec($tranaction);
    $res_data = $result->getData();
//    for ($i = 0; $i < count($res_data); $i++){
//        $r = $res_data[$i]->hash;
//        $r .= "<a href=".get_site_url()."/".$atts['url_page_tx_info']."?hash=&net=></a>";
//    }
    $head = $select_net." network has <span class='te_count_event'>" . count($res_data) . "</span>  transactions";
    $str = "<script type='text/javascript'>
        var te_table = null;
        var te_net_selected = \"".$net."\";
        var te_chain_selected = \"".$chain."\";
        var te_dataSet = ".json_encode($res_data).";
        function te_format(d){
            var hash = d.hash.replace(/\s+/g, '').trim();
            var link_page = \"<a href='".get_site_url()."/".$atts['url_page_tx_info']."?hash=\"+hash+\"&net=\"+te_net_selected+\"' class='tx_new_page_button'>On a new page</a>\";
            var tx_page = TxInfoFromJSONToHTML(d, \"".get_site_url()."/".$atts['url_page_addr_info']."\");
            var data = link_page + tx_page;
            return data;
        }
        document.addEventListener('DOMContentLoaded', function(){
            te_table = jQuery('#te_table_hashes').DataTable({
            dom: '<\"top\"f<\"clear\">rt<\"bottom\"lip<\"clear\">>',
            responsive: true,
            data: te_dataSet,
            columns: [
                { 
                    className: \"details-control\",
                    data:'hash' 
                }
            ],
            createdRow: function (row, data, dataIndex){
                row.id = 'Event'+data.event;
                var tmp = data.hash.replace(/\s+/g, '').trim();
                var link = \"<a href='".get_site_url()."/".$atts['url_page_tx_info']."?hash=\"+tmp+\"&net=\"+te_net_selected+\"' >\"+
                    \"<img src='".plugin_dir_url(__FILE__)."img/add_new_page_icon.png' width='50px;' height='50px;'/></a></td>\";
                var td = row.innerHTML.replace('</td>', link);
                row.innerHTML = td;
            }
         });
        jQuery('#te_table_hashes tbody').on('click', 'td.details-control', function (){
            var tr = jQuery(this).closest('tr');
            var row = te_table.row(tr);
            if (row.child.isShown()){
                row.child.hide();
                tr.removeClass('shown');
            }else{
                row.child(te_format(row.data())).show();
                tr.addClass('shown');
            }
        });
     }, false);
</script>";
    $str .= "<div class='te_hh_head_table' style='text-align: center'>".$head."</div>".
        "<table id='te_table_hashes'><thead><tr><th>Hash Transaction</th></tr></thead></table>";
    return $str;
}
