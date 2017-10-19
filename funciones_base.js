
// Codigo extraido de www.w3schools.com/js/js_cookies.asp
function setCookie(nombre, valor) {
    
    if (typeof(Storage) !== 'undefined') {
        sessionStorage.setItem(nombre, valor);
    }
    else {
        document.cookie = nombre + "=" + encodeURIComponent(valor) + ";";
    }
}

// Codigo extraido de www.w3schools.com/js/js_cookies.asp
function getCookie(nombre) {
    
    if (typeof(Storage) !== 'undefined') {
        var result = sessionStorage.getItem(nombre) + "";
        if (result !== 'null') {
            return result;
        }
    }
    var pnombre = nombre + "=";
    var cookieDecodificada = decodeURIComponent(document.cookie);
    var cookie = cookieDecodificada.split(';');
    for(var i = 0; i < cookie.length; i++) {
        var c = cookie[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(pnombre) === 0) {
            return c.substring(pnombre.length, c.length);
        }
    }
    return 'null';
}

function formatearSoloFecha(fecha) {
    
    if (!fecha) return null;
    var objFecha = new Date(fecha);
    if (objFecha.toString() !== 'Invalid Date') {
        var dia = objFecha.getDate();
        var mes = objFecha.getMonth() + 1;
        var anio = objFecha.getFullYear();

        var resultado = rellenar(anio, 4) + "-" + rellenar(mes) + "-" + rellenar(dia);
        return resultado;
    }
    return null;
}

function formatearFecha(fecha) {
    
    if (!fecha) return null;
    var objFecha = new Date(fecha);
    if (objFecha.toString() !== 'Invalid Date') {
        var dia = objFecha.getDate();
        var mes = objFecha.getMonth() + 1;
        var anio = objFecha.getFullYear();
        var horas = objFecha.getHours();
        var minutos = objFecha.getMinutes();

        var resultado = rellenar(anio, 4) + "-" + rellenar(mes) + "-" + 
                rellenar(dia) + " " + rellenar(horas) + ":" + 
                rellenar(minutos) + ":00";
        return resultado;
    }
    return null;
}

function rellenar(valor, tamano=2, relleno='0') {
    
    valor = valor + "";
    for (var tamActual = valor.length; tamActual < tamano; tamActual++) {
        valor = relleno + valor;
    }
    return valor;
}

function remover(arreglo, indice) {

    return arreglo.slice(0, indice).concat(arreglo.slice(indice + 1));
}

function crear(etiqueta, html=null, clase=null, id=null) {

    var dom = document.createElement(etiqueta);
    if (clase !== null) dom.className = clase;
    if (html !== null) dom.innerHTML = html;
    if (id !== null) dom.id = id;
    return dom;
}

function crearAlerta(tipo='alert-success', mensaje='Exito') {

    var alerta = crear('DIV');
    alerta.className = 'alert alert-dismissable fade in margen-pequeno ' + tipo;
    var cerrar = crear('A');
    cerrar.href = '#';
    cerrar.className = 'close';
    cerrar.setAttribute('data-dismiss', 'alert');
    cerrar.setAttribute('aria-label', 'close');
    cerrar.innerHTML = '&times;';
    var msgContenedor = crear('SPAN');
    msgContenedor.innerHTML = mensaje;

    alerta.appendChild(cerrar);
    alerta.appendChild(msgContenedor);

    return alerta;
}

function mesLiteral(mes) {
    
    mes = parseInt(mes);
    if (mes === 1) return 'Enero';
    if (mes === 2) return 'Febrero';
    if (mes === 3) return 'Marzo';
    if (mes === 4) return 'Abril';
    if (mes === 5) return 'Mayo';
    if (mes === 6) return 'Junio';
    if (mes === 7) return 'Julio';
    if (mes === 8) return 'Agosto';
    if (mes === 9) return 'Septiembre';
    if (mes === 10) return 'Octubre';
    if (mes === 11) return 'Noviembre';
    if (mes === 12) return 'Diciembre';
}

function mesLiteralIngles(mes) {
    
    mes = parseInt(mes);
    if (mes === 1) return 'January';
    if (mes === 2) return 'February';
    if (mes === 3) return 'March';
    if (mes === 4) return 'April';
    if (mes === 5) return 'May';
    if (mes === 6) return 'June';
    if (mes === 7) return 'July';
    if (mes === 8) return 'Augost';
    if (mes === 9) return 'September';
    if (mes === 10) return 'October';
    if (mes === 11) return 'November';
    if (mes === 12) return 'December';
}

// desde 19??-01-01 00:00:00 hasta 9999-12-30 23:59:59
function crearFecha(anio, mes, dia, hora='00', minuto='00', segundo='00') {
    
    return new Date(dia + ' ' + mesLiteralIngles(mes) + ' ' + anio + ' ' + 
            hora + ':' + minuto + ':' + segundo);
}

function ajaxGet(destino, mensaje, callback) {
    
    $.get(destino, mensaje, function(respuesta, estado, xhr) {
        if (estado === 'success') {
            callback(respuesta);
        }
        else {
            console.log('Error: ' + estado);
        }
    }, 'json');
}

function ajaxPost(destino, mensaje, callback) {
    $.post(destino, mensaje, function (respuesta, estado, xhr) {
        if (estado === "success") {
            callback(respuesta);
        }
        else { //"notmodified", "error", "timeout", "parsererror"
            console.log("Error: " + estado);
        }
    }, 'json');
}

// hora com PM o AM
function revisarHoraDentroPeriodoDia(hora) {
    
    if (hora > 24 || hora <= 0 || isNaN(hora)) {
        throw 'El argumento de la hora no esta dentro el rango (AM,PM) esperado';
    }
}

function revisarHora(hora) {
    
    if (hora > 23 || hora < 0 || isNaN(hora)) {
        throw 'El argumetno de la hora no esta dentro el rango esperado';
    }
}

function revisarMinuto(minuto) {
    
    if (minuto < 0 || minuto >= 60 || isNaN(minuto)) {
        throw 'El argumento minutos no estan detro el rango esperado';
    }
}

// hh:mm [AM|PM]
function getMinutos(tiempo) {
    
    var partes = tiempo.split(' ');
    var horaMinuto = partes[0].split(':');
    var periodoDia = partes[1];
    var hora = parseInt(horaMinuto[0]);
    var minuto = parseInt(horaMinuto[1]);
    if (periodoDia !== undefined) revisarHoraDentroPeriodoDia(hora);
    revisarMinuto(minuto);
    if (periodoDia === 'AM' && hora === 12) {
        hora = 0;
        revisarHora(hora);
    } else if (periodoDia === 'PM') {
        if (hora < 12) {
            hora += 12;
        }
        revisarHoraDentroPeriodoDia(hora);
    } else {
        revisarHora(hora);
    }

    return minuto + hora * 60;
}

function invMinutos(minutos) {
    
    var horas = Math.floor(minutos/60);
    var _minutos = minutos - (horas * 60);
    return rellenar(horas) + ":" + rellenar(_minutos); // hh:mm
}

// yyyu-mm-dd [hh-mm[-ss]]
function splitFechaHora(fechaHora) {
    
    var splitFechaHora = fechaHora.split(' ');
    var fecha = splitFechaHora[0];
    var hora = splitFechaHora[1];
    var splitFecha = fecha.split('-');
    
    if (hora === undefined) {
        return {
            anio: parseInt(splitFecha[0]), 
            mes: parseInt(splitFecha[1]), 
            dia: parseInt(splitFecha[2]),
            hora: 0,
            minuto: 0,
            segundo: 0
        };
    }
    var splitHora = hora.split(':');
    return {
        anio: parseInt(splitFecha[0]), 
        mes: parseInt(splitFecha[1]), 
        dia: parseInt(splitFecha[2]),
        hora: parseInt(splitHora[0]),
        minuto: parseInt(splitHora[1]),
        segundo: parseInt(splitHora[2] || 0)
    };
}

// yyyy-mm-dd
function parseSoloFecha(fecha) {
    
    var _fecha = splitFechaHora(fecha);
    return new Date(_fecha.anio, _fecha.mes - 1, _fecha.dia, 0, 0, 0, 0);
}

// yyyy-mm-dd hh:mm[:ss]
function parseFechaHora(fechaHora) {
    
    if (fechaHora.split(' ')[1] === undefined) {
        throw 'La fecha no tiene el formato fecha-hora';
    }
    var fecha = splitFechaHora(fechaHora);
    
    return new Date(
            fecha.anio, fecha.mes - 1, fecha.dia, 
            fecha.hora, fecha.minuto, fecha.segundo, 0);
}

function dias2Milisecs(dias) {
    
    return dias * 24 * 60 * 60 * 1000;
}

function minutos2Milisecs(minutos) {
    
    return minutos * 60 * 1000;
}

function ejecutarSecuencialmente(funciones, argumentos={}, indice=0) {
    
    if (indice === funciones.length) {
        return;
    }
    else {
        funciones[indice](argumentos, function (nuevoArgumento={}) {
            
            ejecutarSecuencialmente(funciones, nuevoArgumento, indice + 1);
        });
    }
}