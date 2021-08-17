<?php
add_action('rest_api_init', function(){
    register_rest_route('eventExplorer/v1', '/info/circulating_suply', array(
        'methods' => 'GET',
        'callback' => 'INFO_get_circulating_suply'
    ));
});

function INFO_get_circulating_suply(WP_REST_Request $request){
    $response = new WP_REST_Response();
    $response->set_data(11900000);
    return $response;
}
