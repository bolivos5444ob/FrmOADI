import './bootstrap';
import "../metronic/src/core/index";
import "../metronic/src/app/layouts/demo1";
// resources/js/app.js

$(document).ready(function() {

    
    alert(15555);

    // Cargar provincias cuando se selecciona un departamento
    $('#cboDepartamento').change(function() {

        alert(52);


        var departamentoId = $(this).val();
        if (departamentoId) {
            $('#cboProvincia').html('<option value=""> - Seleccione- </option>').prop('disabled', true);
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>').prop('disabled', true);

            $.ajax({
                url: '/get-provincias',
                type: 'GET',
                data: { departamento_id: departamentoId },
                success: function(response) {
                    $('#cboProvincia').html(response).prop('disabled', false);
                }
            });
        } else {
            $('#cboProvincia').html('<option value=""> - Seleccione- </option>').prop('disabled', true);
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>').prop('disabled', true);
        }
    });

    // Cargar distritos cuando se selecciona una provincia
    $('#cboProvincia').change(function() {
        var provinciaId = $(this).val();
        if (provinciaId) {
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>').prop('disabled', true);

            $.ajax({
                url: '/get-distritos',
                type: 'GET',
                data: { provincia_id: provinciaId },
                success: funtion(response) {
                    $('#cboDistrito').html(response).prop('disabled', false);
                }
            });
        } else {
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>').prop('disabled', true);
        }
    });
});
