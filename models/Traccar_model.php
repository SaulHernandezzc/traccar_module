class Traccar_model extends App_Model {

public function __construct() {
    parent::__construct();
    $this->load->dbforge();
}

public function apply_migrations() {
    // Tabla dispositivos TRACCAR
    $this->dbforge->add_field([
        'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
        'vehicle_id' => ['type' => 'VARCHAR', 'constraint' => 20],
        'traccar_device_id' => ['type' => 'VARCHAR', 'constraint' => 255],
        'last_sync' => ['type' => 'DATETIME']
    ]);
    $this->dbforge->add_key('id', true);
    $this->dbforge->create_table('traccar_devices', true);

    // Campos adicionales para vehículos
    $fields = [
        'traccar_policy' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '3_facturas'],
        'traccar_blocked' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
    ];
    $this->dbforge->add_column('vehiculos', $fields);
}

public function reverse_migrations() {
    $this->dbforge->drop_table('traccar_devices', true);
    $this->dbforge->drop_column('vehiculos', 'traccar_policy');
    $this->dbforge->drop_column('vehiculos', 'traccar_blocked');
}
}