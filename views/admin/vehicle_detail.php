<div class="traccar-vehicle-detail">
    <h4><?= $vehicle->placa ?></h4>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Información Técnica</h5>
                    <p>IMEI: •••••••••<?= substr(traccar_decrypt($vehicle->imei), -4) ?></p>
                    <p>SIM: •••••••••<?= substr(traccar_decrypt($vehicle->sim), -4) ?></p>
                    <p>Operadora: <?= $vehicle->operadora ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Estado de Facturación</h5>
                    <p>Facturas Pendientes: <?= $pending_invoices ?></p>
                    <p>Próximo Vencimiento: <?= $next_due_date ?></p>
                </div>
            </div>
        </div>
    </div>
</div>