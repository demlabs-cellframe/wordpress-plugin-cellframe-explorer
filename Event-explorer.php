<?php

include_once ("EE-rest-API.php");
include_once("EE-shortcode-dag-info.php");
include_once ("Node_CLI.php");
include_once ("Node_CLI_CMD_Dag.php");
include_once ("Cellframe-explorer_common.php");

add_shortcode("EE_HISTORY", "EE_history");
add_shortcode("EE_GET_INFO_EVENTS", "EE_get_info_events");

function EE_history($atts)
{
    wp_enqueue_script('jquery');
    wp_enqueue_style("datatables",
        plugin_dir_url(__FILE__) . "DataTables/css/dataTables.bootstrap.min.css");
    wp_enqueue_script("datatables",
        plugin_dir_url(__FILE__) . "DataTables/js/jquery.dataTables.js", ['jquery'],
        false, false);
    wp_enqueue_script("CEEEInfo",
        plugin_dir_url(__FILE__). "js/Event-explorer.js");
    if (!isset($_GET['net']))
        $net = $atts['default_net'];
    else
        $net = $_GET['net'];
    if (!isset($_GET['chain']))
        $chain = $atts['default_chain'];
    else
        $chain = $_GET['chain'];
    $addons_page = $atts['dag_info_page'];
    $dag = new Node_CLI_CMD_Dag($net, $chain);
    $dag->event_list();
    $node = new Node_CLI();
    $res = $node->exec($dag);
    $data = $res->getData();
    if ($data == NULL){
        $err_str = "<div class='EE_Error'>Sory, but we were unable to connect to the node, please try again later.</div>";
        return $err_str;
    }
    $chains = CE_Common::strToArray($atts['chains'], ",");
    $nets = CE_Common::strToArray($atts['nets'], ",");
    $rest_url = get_rest_url();
    $rest_url_event = $rest_url."eventExplorer/v1/event/".$net."/";
    $rest_url_list = $rest_url."eventExplorer/v1/list/";
    $select_net = CE_Common::getHtmlSelect($nets, $net, "list-nets", "nets", "
        net_selected = jQuery(\".list-nets option:selected\").val();
        jQuery.ajax({
            url: \"".$rest_url_list."\"+net_selected+\"/\"+chain_selected+\"\",
            dataType: \"json\",
            success: function(data){
                table.clear();
                jQuery(\".count_event\").text(data.count);
                table.rows.add(data.hashes);
                table.draw();
            }
        });
    ");
    $select_chains = CE_Common::getHtmlSelect($chains, $chain, "list-chains", "chains", "
        chain_selected = jQuery(\".list-chains option:selected\").val();
        jQuery.ajax({
            url: \"".$rest_url_list."\"+net_selected+\"/\"+chain_selected+\"\",
            dataType: \"json\",
            success: function(data){
                table.clear();
                jQuery(\".count_event\").text(data.count);
                table.rows.add(data.hashes);
                table.draw();
            }
        });
    ");
    $head = $select_net . " network history contains <span class='count_event'>" . $data->count . "</span> " . $select_chains . " chain events <br/>";
    $table_js = json_encode($data->hashes);
    $str = "<script type='text/javascript'>
    var table = null;
    var dataSet = ".$table_js.";
    var net_selected = \"".$net."\";
    var chain_selected = \"".$chain."\";
    document.addEventListener('DOMContentLoaded', function(){
        function format(dft){
            jQuery.ajax({
                url: \"".$rest_url_event."\"+chain_selected+\"/\"+dft.event+\"\",
                dataType: \"json\",
                success: function(result){
                    var ret = EEInfoFromJSONToHTML(result, \"".get_site_url().$addons_page."\");
                    jQuery('#loadHash'+result.dump_data.event).html(ret);
                }
            });
            return '<div id=\"loadHash'+dft.event+'\"><p>Loading data</p></div>';
        }
        table = jQuery('#table_hashes').DataTable({
            dom: '<\"top\"f<\"clear\">rt<\"bottom\"lip<\"clear\">>',
            responsive: true,
            data: dataSet,
            columns: [
                { 
                    className: \"details-control CE_inf_col_0\",
                    data:'event' 
                },
                { 
                    className: \"details-control CE_inf_col_1\",
                    data:'ts_created' 
                }
            ],
            createdRow: function (row, data, dataIndex){
                jQuery(row).addClass('CE_inf_row')
                row.id = 'Event'+data.event;
                var hash = data.event;
                var link = \"<a href='".get_site_url().$addons_page."&chain=\"+chain_selected+\"&net=\"+net_selected+\"&hash=\"+hash+\"' >\"+
                    \"<img src='".plugin_dir_url(__FILE__)."img/add_new_page_icon.png' width='12px;' height='12px;'/></a></td>\";
                var tmp = row.innerHTML.replace('</td>',link);
                row.innerHTML = tmp;
            }
         });
        jQuery('#table_hashes tbody').on('click', 'td.details-control', function (){
            var tr = jQuery(this).closest('tr');
            var row = table.row(tr);
            if (row.child.isShown()){
                row.child.hide();
                tr.removeClass('shown');
            }else{
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
        jQuery('#table_hashes tbody').addClass('CE_inf_tbody')
     }, false);
    ";
    $str .= "</script>";
    $str .= "<div class='hh_head_table' style='text-align: center'>".$head."</div>".
    "<table id='table_hashes'><thead class='CE_thead'><tr class='CE_th_row'><th>Hash</th><th>TS</th></tr></thead></table>";
    return $str;
}

