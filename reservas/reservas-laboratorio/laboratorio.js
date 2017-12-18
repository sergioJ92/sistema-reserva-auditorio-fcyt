
$(document).ready(function() {
    $('#laboratorio-oculto').css("display", "block");
    
    var valor = $('#selAnioGestion').val();
    if (valor !== 'null') {
        var anio = parseInt(valor.split('-')[0]);
        var gestion = parseInt(valor.split('-')[1]);
        cargarCalendario(anio, gestion);
    }
});

var departamento = '';
var ambiente = '';

$('#selAnioGestion').attr('disabled', 'disabled');
$( "#selLaboratorio" ).attr('disabled', 'disabled');

$('#selDepartamento').change(function () {
    ambiente = '';
    departamento = $(this).val();
    console.log(departamento);
    var _departamento = departamento.replace(/\s/g,"_");
    $("#selLaboratorio").load("obtener_datos.php?tipo="+"3"+"&nombre="+_departamento);
    $("#selLaboratorio").removeAttr("disabled");
});

$("#selLaboratorio").change(function(){
    ambiente = $(this).val();
    console.log(ambiente);
    $("#selAnioGestion").removeAttr("disabled");
});
