$(document).ready(function() {
    // Actualización en tiempo real
    setInterval(function() {
        $.ajax({
            url: admin_url + 'traccar_module/refresh_status',
            success: function(response) {
                $('#vehicle-status-container').html(response);
            }
        });
    }, 30000);
});