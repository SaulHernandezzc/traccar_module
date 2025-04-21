<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Traccar_module extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('traccar_model');
    }

    public function install() {
        $this->traccar_model->apply_migrations();
        return true;
    }

    public function uninstall() {
        $this->traccar_model->reverse_migrations();
        return true;
    }

    public function index() {
        $data['vehicles'] = $this->traccar_model->get_all_vehicles();
        $this->load->view('admin/dashboard', $data);
    }
}