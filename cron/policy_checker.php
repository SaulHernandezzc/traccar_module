<?php
require_once dirname(__FILE__) . '/../../config.php';

$CI = &get_instance();
$CI->load->model('traccar_model');

// Verificar políticas cada 15 minutos
$vehicles = $CI->traccar_model->get_vehicles_for_policy_check();

foreach($vehicles as $vehicle) {
    $should_block = $CI->traccar_model->check_block_policy($vehicle->id);
    
    if($should_block && !$vehicle->blocked) {
        $CI->traccar_model->block_vehicle($vehicle->id);
    } elseif(!$should_block && $vehicle->blocked) {
        $CI->traccar_model->unblock_vehicle($vehicle->id);
    }
}

log_activity('Policy checker executed: ' . date('Y-m-d H:i:s'));