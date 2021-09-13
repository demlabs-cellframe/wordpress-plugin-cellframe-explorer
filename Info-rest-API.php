<?php
add_action('rest_api_init', function(){
    if (file_exists("/var/www/cfgs/suply")){
        register_rest_route('eventExplorer/v1', '/info/circulating_suply', array(
            'methods' => 'GET',
            'callback' => 'INFO_get_circulating_suply'
        ));
    } else {
        error_log("Can't open config file /var/www/cfgs/suplu");
    }
});

function INFO_get_circulating_suply(WP_REST_Request $request){
    $fp = fopen("/var/www/cfgs/suply", "rt");
    if (!$fp){
        error_log("Can't open config file /var/www/cfgs/suplu");
#        $responce->set_code(404);
        wp_send_json_error(null, 404);
    }
    $suply = fgets($fp);
    $response = new WP_REST_Response();
    $response->set_data(intval($suply));
    return $response;
}
