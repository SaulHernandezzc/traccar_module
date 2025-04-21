<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('traccar_model');
    }

    // GET /api/traccar/vehicles
    public function vehicles_get() {
        $client_id = $this->get('client_id');
        $vehicles = $this->traccar_model->get_client_vehicles($client_id);
        $this->response($vehicles, 200);
    }

    // POST /api/traccar/sync
    public function sync_post() {
        $vehicle_id = $this->post('vehicle_id');
        $this->traccar_model->force_sync($vehicle_id);
        $this->response(['status' => 'success'], 200);
    }
}