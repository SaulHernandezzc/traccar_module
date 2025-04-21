<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Module name
 */
define('TRACCAR_MODULE_NAME', 'traccar_module');

/**
 * Perfex CRM Traccar Module Information
 *
 * @return array
 */
function traccar_module_info()
{
    // Información del módulo basada en tus datos
    $info = [
        'built_by'     => 'GPS Tracker International',
        'name'         => 'Traccar for Perfex', // Nombre legible del módulo
        'version'      => '1.0.0', // Versión inicial (ajustada a formato común)
        'description'  => 'Comprehensive Fleet Management and Automated Billing Solution for Perfex CRM. Integrates with Traccar GPS tracking to link vehicles to Perfex clients, manage recurring invoices per vehicle, implement customizable blocking policies based on payment status, and provide detailed operational dashboards. Designed for businesses managing vehicle fleets and requiring automated billing and service control.', // Descripción mejorada basada en tu contexto
        'requires_perfex_version' => '3.0.0', // Versión mínima de Perfex requerida (ajusta si sabes cuál es)
        'author_url'   => 'https://www.trackerinternationalinc.com', // Tu URL
        // 'activate_after_install' => true // Descomenta esta línea si quieres que el módulo se active automáticamente al subirlo
    ];

    return $info;
}

/**
 * Install the module
 *
 * @return boolean
 */
function traccar_module_install()
{
    $CI = &get_instance();

    // ** LOGICA DE INSTALACION DE TU MODULO **

    // --- CREACION DE TABLAS ---
    // Usaremos dbforge para crear la(s) tabla(s) necesaria(s).
    // Basado en tu modelo, crearemos una tabla para los vehículos asociados a clientes de Perfex.
    $CI->load->dbforge();

    // Tabla para almacenar información de vehículos y su relación con clientes Perfex y dispositivos Traccar
    $table_name_vehicles = db_prefix() . 'traccar_vehicles'; // Nombre de la tabla: Ej. tbltraccar_vehicles

    $fields_vehicles = [
        'id' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'auto_increment' => TRUE,
        ],
        'perfex_customer_id' => [ // Enlace al cliente en Perfex (tblclients.userid)
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'null'           => TRUE, // Puede ser NULL si un vehículo no está directamente asociado a un cliente al inicio? O debería ser NOT NULL? Decide según tu lógica.
        ],
        'traccar_device_id' => [ // ID del dispositivo en Traccar. Crucial para la integración.
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'unique'         => TRUE, // Asumiendo que cada dispositivo Traccar solo se enlaza a un registro de vehículo aquí.
        ],
        'plate_number' => [ // Placa del vehículo
            'type'           => 'VARCHAR',
            'constraint'     => '50',
            'null'           => TRUE, // O FALSE si es requerido
        ],
        'imei' => [ // IMEI del dispositivo (identificador único en Traccar y aquí)
            'type'           => 'VARCHAR',
            'constraint'     => '20',
            'unique'         => TRUE, // El IMEI debe ser único
            'null'           => FALSE, // El IMEI es un identificador clave
        ],
        'sim_number' => [ // Número SIM asociado al dispositivo
            'type'           => 'VARCHAR',
            'constraint'     => '20',
            'null'           => TRUE,
             // Considera si este campo debe ser encriptado por seguridad
        ],
        'operator' => [ // Operador de la SIM
            'type'           => 'VARCHAR',
            'constraint'     => '50',
            'null'           => TRUE,
        ],
        'blocking_policy' => [ // Política de bloqueo aplicada a este vehículo
            'type'           => 'VARCHAR',
            'constraint'     => '50', // Ej: 'inmediato', '3_facturas', '6_facturas', 'personalizado'
            'null'           => TRUE, // O FALSE si cada vehículo debe tener una política asignada
        ],
         'recurring_invoice_id' => [ // Enlace opcional a una factura recurrente de Perfex (tblrecurring_invoices.id)
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'null'           => TRUE, // Un vehículo puede existir sin una factura recurrente asociada inicialmente
         ],
        'date_added' => [ // Fecha en que se agregó el vehículo al sistema
            'type'           => 'DATETIME',
        ],
         'active' => [ // Estado de actividad del vehículo en el sistema (no en Traccar)
            'type'           => 'INT',
            'constraint'     => 1,
            'default'        => 1, // 1 = Activo, 0 = Inactivo
         ],
         'is_blocked_in_traccar' => [ // Indica si el vehículo está marcado como bloqueado en Traccar por el módulo
             'type'           => 'INT',
             'constraint'     => 1,
             'default'        => 0, // 0 = No bloqueado, 1 = Bloqueado
         ],
         // Agrega aquí otros campos que necesites, como campos para guardar el último reporte de posición, etc.
         // 'last_position_data' => [
         //     'type'           => 'TEXT', // Para guardar la última posición en formato JSON o similar
         //     'null'           => TRUE,
         // ],
         // 'last_position_time' => [
         //     'type'           => 'DATETIME',
         //     'null'           => TRUE,
         // ],
    ];

    $CI->dbforge->add_field($fields_vehicles);
    $CI->dbforge->add_key('id', TRUE); // Define 'id' como clave primaria
    $CI->dbforge->add_key('perfex_customer_id'); // Indexar por cliente para búsquedas rápidas
    $CI->dbforge->add_key('traccar_device_id'); // Indexar por ID de Traccar para búsquedas rápidas
    $CI->dbforge->add_key('imei'); // Indexar por IMEI
     $CI->dbforge->add_key('recurring_invoice_id'); // Indexar por factura recurrente


    if (!$CI->db->table_exists($table_name_vehicles)) {
        $CI->dbforge->create_table($table_name_vehicles);
    }
    // --- FIN CREACION DE TABLAS ---

    // --- AGREGAR OPCIONES DE CONFIGURACION ---
    // Si necesitas guardar la URL de la API de Traccar, credenciales, etc.
    // Usa la función `add_option` de Perfex.
    // Aquí ejemplos, ADAPTA O AGREGA LAS OPCIONES QUE NECESITES REALMENTE.
    $traccar_options = [
        'traccar_api_url' => 'http://tu_servidor_traccar:8082/api/', // URL base de la API de Traccar
        'traccar_api_username' => '', // Usuario de la API de Traccar (si usas autenticación básica)
        'traccar_api_password' => '', // Contraseña de la API de Traccar (si usas autenticación básica)
        // O si usas clave de API: 'traccar_api_key' => '',
        'traccar_default_blocking_policy' => '3_facturas', // Política de bloqueo por defecto para nuevos vehículos
        'traccar_invoice_check_cron_interval' => 'daily', // Frecuencia con la que el cron job revisa facturas (ej: daily, hourly)
        'traccar_sim_imei_encryption_key' => bin2hex(random_bytes(16)), // Clave para encriptar SIM e IMEI (GENERA UNA ÚNICA VEZ Y GUARDA SEGURO)
        // Agrega aquí cualquier otra opción de configuración que tu módulo necesite
    ];

    foreach ($traccar_options as $name => $value) {
        // add_option(option_name, option_value, autoload)
        // autoload = 1 significa que la opción se carga automáticamente al iniciar Perfex
        add_option($name, $value, 1);
    }
    // --- FIN AGREGAR OPCIONES ---

    // ** AQUI PODRIAS AGREGAR OTRA LOGICA DE INICIALIZACION SI ES NECESARIO **
    // Por ejemplo, registrar hooks, agregar permisos, etc.

    // Si la instalación fue exitosa, retorna true
    return true;
}

/**
 * Uninstall the module
 *
 * @return boolean
 */
function traccar_module_uninstall()
{
     $CI = &get_instance();

     // ** LOGICA DE DESINSTALACION DE TU MODULO **

     // --- ELIMINACION DE TABLAS ---
     // Elimina las tablas que creaste durante la instalación.
     $CI->load->dbforge();

     $table_name_vehicles = db_prefix() . 'traccar_vehicles'; // Nombre de la tabla a eliminar

     if ($CI->db->table_exists($table_name_vehicles)) {
         $CI->dbforge->drop_table($table_name_vehicles);
     }
     // --- FIN ELIMINACION DE TABLAS ---

     // --- ELIMINAR OPCIONES DE CONFIGURACION ---
     // Elimina las opciones de configuración que agregaste.
     $traccar_option_names = [
        'traccar_api_url',
        'traccar_api_username',
        'traccar_api_password',
        // 'traccar_api_key', // Descomentar si usas clave de API
        'traccar_default_blocking_policy',
        'traccar_invoice_check_cron_interval',
        'traccar_sim_imei_encryption_key',
        // Agrega aquí los nombres de todas las opciones que agregaste en install
     ];

     foreach ($traccar_option_names as $name) {
        delete_option($name);
     }
     // --- FIN ELIMINAR OPCIONES ---

     // ** AQUI PODRIAS AGREGAR OTRA LOGICA DE LIMPIEZA SI ES NECESARIO **
     // Por ejemplo, eliminar archivos de configuración, limpiar caches, etc.

     // Si la desinstalación fue exitosa, retorna true
     return true;
}

// Opcional: Funciones que se ejecutan al activar y desactivar el módulo desde la interfaz
// Puedes agregar lógica aquí si necesitas hacer algo específico al activar o desactivar el módulo
/*
function traccar_module_activation_action()
{
    // Código a ejecutar al activar el módulo
}

function traccar_module_deactivation_action()
{
    // Código a ejecutar al desactivar el módulo
}
*/
