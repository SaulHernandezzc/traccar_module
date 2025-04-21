<?php
defined('BASEPATH') or exit('No direct script access allowed');

register_activation_hook('traccar_module', 'traccar_activate');
register_deactivation_hook('traccar_module', 'traccar_deactivate');

hooks()->add_action('admin_init', 'traccar_add_menu');

function traccar_add_menu() {
    $CI = &get_instance();
    $CI->app_menu->add_sidebar_children_item('utilities', [
        'slug' => 'traccar_module',
        'name' => $CI->lang->line('module_name'),
        'href' => admin_url('traccar_module'),
        'icon' => 'fa fa-satellite-dish'
    ]);
}

function traccar_activate() {
    $CI = &get_instance();
    $CI->load->model('traccar_model');
    $CI->traccar_model->apply_migrations();
}

function traccar_deactivate() {
    $CI = &get_instance();
    $CI->load->model('traccar_model');
    $CI->traccar_model->reverse_migrations();
}