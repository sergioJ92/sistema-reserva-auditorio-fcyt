$(document).ready(function() {
    $('#auditorio-oculto').css("display", "block");    
    var valor = $('#selAnioGestion').val();
    if (valor !== 'null') {
        var anio = parseInt(valor.split('-')[0]);
        var gestion = parseInt(valor.split('-')[1]);
        cargarCalendario(anio, gestion);
    }
});

var ambiente='';

$('#selAnioGestion').attr('disabled', 'disabled');

$('#selAuditorio').change(function () {
    var _auditorio = $(this).val();
    console.log(_auditorio);
    ambiente = _auditorio;
    $("#selAnioGestion").removeAttr("disabled");
});
