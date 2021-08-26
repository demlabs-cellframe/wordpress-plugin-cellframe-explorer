<?php
include_once ("Node_CLI_CMD_Dag.php");
include_once ("Node_CLI.php");
add_action('rest_api_init', function(){
    register_rest_route('eventExplorer/v1', '/event/(?P<e_net>.+)/(?P<e_chain>.+)/(?P<e_hash>.+)', array(
        'methods' => 'GET',
        'callback' => 'EE_get_info_about_hash'
    ));
    register_rest_route('eventExplorer/v1', '/list/(?P<e_net>.+)/(?P<e_chain>.+)', array(
        'methods' => 'GET',
        'callback' => 'EE_get_list_hash'
    ));
});

function EE_get_list_hash(WP_REST_Request $request){
    $net = $request->get_param('e_net');
    $chain = $request->get_param('e_chain');
    $dag = new Node_CLI_CMD_Dag($net, $chain);
    $dag->event_list();
    $node = new Node_CLI();
    $result = $node->exec($dag);
    $data = $result->getData();
    $response = new WP_REST_Response();
    if ($data == NULL)
        $response->set_status(500);
    $response->set_data($data);
    return $response;
}

function EE_get_info_about_hash(WP_REST_Request $request){
    $e_hash = $request->get_param('e_hash');
    $net = $request->get_param('e_net');
    $chain = $request->get_param('e_chain');
    $dag = new Node_CLI_CMD_Dag($net, $chain);
    $dag->event_dump($e_hash);
    $node = new Node_CLI();
    $result = $node->exec($dag);
    $data = $result->getData();
    $response = new WP_REST_Response();
    if ($data == NULL)
        $response->set_status(500);
    $response->set_data($data);
    return $response;
}
