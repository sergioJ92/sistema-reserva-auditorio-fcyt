
$(document).ready(function() {
    $('#aula-oculto').css("display", "block");
    
    var valor = $('#selAnioGestion').val();
    if (valor !== 'null') {
        var anio = parseInt(valor.split('-')[0]);
        var gestion = parseInt(valor.split('-')[1]);
        cargarCalendario(anio, gestion);
    }
});

var edificio = '';
var piso = '';
var ambiente = '';



$('#selAnioGestion').attr('disabled', 'disabled');

$( "#selPiso" ).attr('disabled', 'disabled');

$( "#selAula" ).attr('disabled', 'disabled');

$('#selEdificio').change(function () {
    piso = '';
    ambiente = '';
    edificio = $(this).val();
    var _edificio = edificio.replace(/\s/g,"_");
    $("#selPiso").load("obtener_datos.php?tipo="+"1"+"&nombre="+_edificio);
    $("#selPiso").removeAttr("disabled");
    $('#selAula').html("");
    $('#selAula').attr('disabled', 'disabled');
});

$('#selPiso').change(function () {
    ambiente = '';
    piso = $(this).val();
    var _edificio = edificio.replace(/\s/g,"_");
    var _piso = piso.replace(/\s/g,"_");
    console.log(edificio);
    console.log(piso);
    $("#selAula").load("obtener_datos.php?tipo="+"2"+"&nombre="+_edificio+"&piso="+_piso);
    $("#selAula").removeAttr("disabled");
});

$('#selAula').change(function () {
    ambiente = $(this).val();
    console.log(ambiente);
    $("#selAnioGestion").removeAttr("disabled");
});
