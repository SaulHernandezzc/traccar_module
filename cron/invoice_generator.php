<?php
require_once dirname(__FILE__) . '/../../config.php';

$traccar = new Traccar_module();
$traccar->generate_recurring_invoices();

echo "Facturas generadas: " . date('Y-m-d H:i:s');