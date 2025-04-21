<?php traccar_check_permission('customer') ?>

<div class="client-traccar-view">
    <h4>Mis Vehículos</h4>
    
    <?php if(empty($vehicles)): ?>
        <div class="alert alert-info">No tienes vehículos registrados</div>
    <?php else: ?>
        <?php foreach($vehicles as $v): ?>
        <div class="client-vehicle-card mb-3">
            <div class="card">
                <div class="card-body">
                    <h5><?= $v['placa'] ?></h5>
                    <p class="mb-1">Estado: 
                        <span class="badge badge-<?= $v['blocked'] ? 'danger' : 'success' ?>">
                            <?= $v['blocked'] ? 'Bloqueado' : 'Activo' ?>
                        </span>
                    </p>
                    <p class="mb-0">Última Actualización: <?= $v['last_connection'] ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>