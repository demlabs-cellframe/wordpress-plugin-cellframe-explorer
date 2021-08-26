<?php
include_once("Event-explorer.php");
include_once("Transaction-explorer.php");

add_shortcode("CCE_HISTORY", "CCE_History");
function CCE_History($atts){
    wp_enqueue_script('jquery');
    wp_enqueue_style("datatables",
        plugin_dir_url(__FILE__) . "DataTables/css/dataTables.bootstrap.min.css");
    wp_enqueue_style("ce_tabs_style",
        plugin_dir_url(__FILE__) . "css/tabs.css");
    wp_enqueue_script("datatables",
        plugin_dir_url(__FILE__) . "DataTables/js/jquery.dataTables.js", ['jquery'],
        false, false);
    wp_enqueue_script("CEEEInfo",
        plugin_dir_url(__FILE__). "js/Event-explorer.js");
    wp_enqueue_script("CETabs",
        plugin_dir_url(__FILE__). "js/tabs.js", ['jquery'], false, false);

    $EE_page = EE_history(array(
        "default_net" => $atts["default_net"],
        "default_chain" => $atts["default_chain"],
        "dag_info_page" => $atts["dag_info_page"],
        "chains" => $atts["chains"],
        "nets" => $atts["nets"]
    ));
    $TE_page = TE_history(array(
        "default_net" => $atts["default_net"],
        "default_chain" => $atts["default_chain"],
        "chains" => $atts["chains"],
        "nets" => $atts["nets"],
        "url_page_tx_info" => $atts["url_page_tx_info"],
        "url_page_addr_info" => $atts["url_page_addr_info"]
    ));
    $str = '
<section class="wrapper">
	<ul class="tabs">
		<li class="active">Event explorer</li>
		<li>Transaction explorer</li>
	</ul>

	<ul class="tab__content">
		<li class="active">
			<div class="content__wrapper">';
				$str .= $EE_page;
			$str .= '</div>
		</li>
		<li hidden>
			<div class="content__wrapper">';
                                $str .= $TE_page;
			$str .= '</div>
		</li>
	</ul>
</section>';
    return $str;
}

?>
