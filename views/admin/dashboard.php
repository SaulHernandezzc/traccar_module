<div class="traccar-dashboard">
    <h3><?= _l('traccar_module') ?></h3>
    
    <div class="row">
        <div class="col-md-4">
            <div class="vehicle-status-card status-active">
                <h5>Vehículos Activos</h5>
                <h2><?= $active_vehicles ?></h2>
            </div>
            <div class="vehicle-status-card status-blocked mt-3">
                <h5>Vehículos Bloqueados</h5>
                <h2><?= $blocked_vehicles ?></h2>
            </div>
        </div>
        
        <div class="col-md-8">
            <table class="table traccar-table">
                <thead>
                    <tr>
                        <th><?= _l('vehicle') ?></th>
                        <th>Política</th>
                        <th><?= _l('last_connection') ?></th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($vehicles as $v): ?>
                    <tr>
                        <td><?= $v['placa'] ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $v['politica_bloqueo'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['last_connection'])) ?></td>
                        <td>
                            <span class="badge badge-<?= $v['blocked'] ? 'danger' : 'success' ?>">
                                <?= $v['blocked'] ? 'Bloqueado' : 'Activo' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>