<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('traccar_encrypt')) {
    function traccar_encrypt($data) {
        $CI = &get_instance();
        return $CI->encryption->encrypt($data);
    }
}

if (!function_exists('traccar_decrypt')) {
    function traccar_decrypt($ciphertext) {
        $CI = &get_instance();
        return $CI->encryption->decrypt($ciphertext);
    }
}

if (!function_exists('traccar_check_permission')) {
    function traccar_check_permission($required_role) {
        $CI = &get_instance();
        if ($CI->session->userdata('role') != $required_role) {
            show_error('Acceso no autorizado', 403);
        }
    }
}