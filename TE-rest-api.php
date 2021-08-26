<?php
include_once ("Node_CLI_CMD_Ledger.php");
include_once ("Node_CLI.php");
add_action('rest_api_init', function(){
//    register_rest_route('transactionExplorer/v1', '/event/(?P<e_net>.+)/(?P<e_chain>.+)/(?P<e_hash>.+)', array(
//        'methods' => 'GET',
//        'callback' => 'EE_get_info_about_hash'
//    ));
    register_rest_route('transactionExplorer/v1', '/list/(?P<e_net>.+)', array(
        'methods' => 'GET',
        'callback' => 'EE_get_list_transaction'
    ));
});

function EE_get_list_transaction(WP_REST_Request $request){
    $net = $request->get_param('e_net');
    $legder = new Node_CLI_CMD_Ledger($net);
    $legder->ledger_tx_all();
    $node = new Node_CLI();
    $result = $node->exec($legder);
    $data = $result->getData();
    $response = new WP_REST_Response();
    if ($data == NULL)
        $response->set_status(500);
    $response->set_data($data);
    return $response;
}
