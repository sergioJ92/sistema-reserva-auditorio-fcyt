
const COLOR_CRONOGRAMA = '#fff';
const COLOR_EVENTO_PERMITE_RESERVA = '#ecec68';
const COLOR_EVENTO_CIERRE = 'lightcoral';
const COLOR_EVENTO_TOLERANCIA = 'lightgreen';
const COLOR_EVENTO_OTRO = '#a8cddc';
const COLOR_EVENTO_INVALIDADOR ='#aba7a7';
const COLOR_EVENTO_RESERVA = '#bc79e4';

const NUMERO_DIAS_SEMANA = 6;
const INICIO_TIEMPOS = parseFechaHora('1980-01-01 00:00:00');
const FIN_TIEMPOS = parseFechaHora('9999-12-30 23:59:59');

function esNulo(dato) {

    return dato === null || dato === undefined;
}

function abrirModal(domModal) {
    
    $(domModal).modal('show');
}

function cerrarModal(domModal) {
    
    $(domModal).modal('hide');
}

class DialogoModal {
    
    constructor(id, titulo, claseDialogo='', claseCabecera='') {
        
        this.id = id;
        this.campoTitulo = crear('H4', titulo, 'modal-title');
        this.claseDialogo = claseDialogo;
        this.claseCabecera = claseCabecera;
    }
    
    // debe ser sobreescrito por sus hijos
    construirCuerpo() {
        
        return crear('DIV', null, 'row');
    }
    
    construirPie() {
        
        let contenedor = crear('DIV', null, 'row text-center');
        let btnCerrarInferior = crear('BUTTON', 'Cerrar', 'btn btn-default');
        btnCerrarInferior.setAttribute('type', 'button');
        btnCerrarInferior.setAttribute('data-dismiss', 'modal');
        contenedor.appendChild(btnCerrarInferior);
        return contenedor;
    }
    
    getDOM() {
        
        let modal = crear('DIV', null, 'modal fade', this.id);
        modal.setAttribute('role', 'dialog');
        let dialogo = crear('DIV', null, 'modal-dialog ' + this.claseDialogo);
        dialogo.setAttribute('role', 'document');
        let contenidoModal = crear('DIV', null, 'modal-content');
        
        let cabeceraModal = crear('DIV', null, 'modal-header ' + this.claseCabecera);
        let btnCerrarSuperior = crear('BUTTON', '&times;', 'close');
        btnCerrarSuperior.setAttribute('type', 'button');
        btnCerrarSuperior.setAttribute('data-dismiss', 'modal');
        cabeceraModal.appendChild(btnCerrarSuperior);
        cabeceraModal.appendChild(this.campoTitulo);
        
        let cuerpoModal = crear('DIV', null, 'modal-body');
        cuerpoModal.appendChild(this.construirCuerpo());
        
        let pieModal = crear('DIV', null, 'modal-footer');
        pieModal.appendChild(this.construirPie());
        
        contenidoModal.appendChild(cabeceraModal);
        contenidoModal.appendChild(cuerpoModal);
        contenidoModal.appendChild(pieModal);
        
        dialogo.appendChild(contenidoModal);
        modal.appendChild(dialogo);
        
        return modal;
    }
}

class ModalInformativo extends DialogoModal {
    
    constructor(id, claseDialogo, claseCabecera, titulo, mensaje) {
        
        super(id, titulo, claseDialogo, claseCabecera);
        this.mensaje = mensaje;
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        let div = crear('DIV', this.mensaje, 'col-xs-12');
        contenedor.appendChild(div);
        return contenedor;
    }
}

class ModalCronograma extends DialogoModal {
    
    constructor(id, anio, gestion) {
        
        super(id, 'Cronograma ' + anio + ' - ' + gestion, 'modal-sm');
        this.campoAnio = crear('SPAN', anio);
        this.campoGestion = crear('SPAN', gestion);
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        let contenedorAnio = crear('DIV', null, 'col-xs-6');
        let marcadorAnio = crear('B', 'Anio: ');
        contenedorAnio.appendChild(marcadorAnio);
        contenedorAnio.appendChild(this.campoAnio);
        let contenedorGestion = crear('DIV', null, 'col-xs-6');
        let marcadorGestion = crear('B', 'Gestion: ');
        contenedorGestion.appendChild(marcadorGestion);
        contenedorGestion.appendChild(this.campoGestion);
        contenedor.appendChild(contenedorAnio);
        contenedor.appendChild(contenedorGestion);
        return contenedor;
    }
}

class ModalContenido extends DialogoModal {
    
    constructor(id, inicio, fin, titulo, descripcion) {
        
        super(id, titulo, 'modal-sm');
        this.campoInicio = crear('SPAN', this.formatearFechaHora(inicio));
        this.campoFin = crear('SPAN', this.formatearFechaHora(fin));
        if (!esNulo(descripcion) && descripcion.length > 0) {
            this.campoDescripcion = crear('P', descripcion);
        }
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        let contenedorInicio = crear('DIV', null, 'col-xs-6 padding-pequeno-bottom');
        let marcadorInicio = crear('B', 'Inicio:<br/>');
        contenedorInicio.appendChild(marcadorInicio);
        contenedorInicio.appendChild(this.campoInicio);
        let contenedorFin = crear('DIV', null, 'col-xs-6 padding-pequeno-bottom');
        let marcadorFin = crear('B', 'Fin:<br/>');
        contenedorFin.appendChild(marcadorFin);
        contenedorFin.appendChild(this.campoFin);
        contenedor.appendChild(contenedorInicio);
        contenedor.appendChild(contenedorFin);
        if (!esNulo(this.campoDescripcion)) {
            let contenedorDescripcion = crear('DIV');
            let contenedorMarcador = crear('DIV', '<b>Descripcion:<b>', 'col-xs-4');
            let contenedorCampo = crear('DIV', null, 'col-xs-8');
            contenedorCampo.appendChild(this.campoDescripcion);
            contenedorDescripcion.appendChild(contenedorMarcador);
            contenedorDescripcion.appendChild(contenedorCampo);
            contenedor.appendChild(contenedorDescripcion);
        }
        return contenedor;
    }
    
    formatearFechaHora(fecha) {
        
        let dia = fecha.getDate();
        let mes = fecha.getMonth() + 1;
        let anio = fecha.getFullYear();
        let horas = fecha.getHours();
        let minutos = fecha.getMinutes();

        let formateado = rellenar(anio, 4) + "-" + rellenar(mes) + "-" + 
                rellenar(dia) + ' ' + rellenar(horas) + ':' + rellenar(minutos);
        return formateado;
    }
}

class ModalVerReserva extends DialogoModal {
    
    constructor(id, inicio, fin, evento) {
        
        super(id, 'Reserva', 'modal-sm', 'cabecera-reservar');
        this.campoFecha = crear('SPAN', this.obtenerFechaFormateada(inicio));
        this.campoHoraInicio = crear('SPAN', this.obtenerHoraFormateada(inicio));
        this.campoHoraFin = crear('SPAN', this.obtenerHoraFormateada(fin));
        this.campoEvento = crear('SPAN', evento? evento : 'Reserva académica');
    }
    
    obtenerFechaFormateada(fecha) {
        
        let anio = fecha.getFullYear();
        let mes = fecha.getMonth();
        let dia = fecha.getDate();
        
        return rellenar(dia) + '-' + rellenar(mes) + '-' + rellenar(anio, 4);
    }
    
    obtenerHoraFormateada(fecha) {
        
        let hora = fecha.getHours();
        let minuto = fecha.getMinutes();
        
        return rellenar(hora) + ':' + rellenar(minuto);
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        
        let contenedorFecha = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorFecha = crear('B', 'Fecha: ');
        contenedorFecha.appendChild(marcadorFecha);
        contenedorFecha.appendChild(this.campoFecha);
        
        let contenedorHoraInicio = crear('DIV', null, 'col-xs-6 padding-pequeno-bottom');
        let marcadorHoraInicio = crear('B', 'Hora inicio: ');
        contenedorHoraInicio.appendChild(marcadorHoraInicio);
        contenedorHoraInicio.appendChild(this.campoHoraInicio);
        
        let contenedorHoraFin = crear('DIV', null, 'col-xs-6 padding-pequeno-bottom');
        let marcadorHoraFin = crear('B', 'Hora Fin: ');
        contenedorHoraFin.appendChild(marcadorHoraFin);
        contenedorHoraFin.appendChild(this.campoHoraFin);
        
        let contenedorNombreEvento = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let contenedorMarcadorEvento = crear('B', 'Evento: ');
        contenedorNombreEvento.appendChild(contenedorMarcadorEvento);
        contenedorNombreEvento.appendChild(this.campoEvento);
        
        contenedor.appendChild(contenedorFecha);
        contenedor.appendChild(contenedorHoraInicio);
        contenedor.appendChild(contenedorHoraFin);
        contenedor.appendChild(contenedorNombreEvento);
        
        return contenedor;
    }
    
    formatearFechaHora(fecha) {
        
        let dia = fecha.getDate();
        let mes = fecha.getMonth() + 1;
        let anio = fecha.getFullYear();
        let horas = fecha.getHours();
        let minutos = fecha.getMinutes();

        let formateado = rellenar(anio, 4) + "-" + rellenar(mes) + "-" + 
                rellenar(dia) + ' ' + rellenar(horas) + ':' + rellenar(minutos);
        return formateado;
    }
}

class ModalVerReservaSolicitada extends ModalVerReserva {
    
    constructor(id, inicio, fin, evento, responsable, institucion, descripcion) {
        
        super(id, inicio, fin, evento);
        this.campoTitulo = crear('H4', 'Reserva Solicitada', 'modal-title');
        this.responsable = responsable;
        this.institucion = institucion? institucion : 'Ninguno';
        this.descripcion = descripcion? descripcion : 'Ninguno';
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        
        let contenedorResponsable = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorResponsable = crear('B', 'Responsable: ');
        let campoResponsable = crear('SPAN', this.responsable);
        contenedorResponsable.appendChild(marcadorResponsable);
        contenedorResponsable.appendChild(campoResponsable);
        
        let contenedorInstitucion = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorInstitucion = crear('B', 'Institución: ');
        let campoInstitucion = crear('SPAN', this.institucion);
        contenedorInstitucion.appendChild(marcadorInstitucion);
        contenedorInstitucion.appendChild(campoInstitucion);
        
        let contenedorDescripcion = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorDescripcion = crear('B', 'Descripción: ');
        let campoDescripcion = crear('SPAN', this.descripcion);
        contenedorDescripcion.appendChild(marcadorDescripcion);
        contenedorDescripcion.appendChild(campoDescripcion);
        
        contenedor.appendChild(contenedorResponsable);
        contenedor.appendChild(contenedorInstitucion);
        contenedor.appendChild(contenedorDescripcion);
        
        return contenedor;
    }
}

class ModalVerReservaAcademica extends ModalVerReserva {
    
    constructor(id, inicio, fin, evento, responsable, materia, asunto) {
        
        super(id, inicio, fin, evento);
        this.responsable = responsable;
        this.materia = materia;
        this.asunto = asunto;
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        
        let contenedorResponsable = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorResponsable = crear('B', 'Responsable: ');
        let campoResponsable = crear('SPAN', this.responsable);
        contenedorResponsable.appendChild(marcadorResponsable);
        contenedorResponsable.appendChild(campoResponsable);
        
        let contenedorMateria = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorMateria = crear('B', 'Materia: ');
        let campoMateria = crear('SPAN', this.materia);
        contenedorMateria.appendChild(marcadorMateria);
        contenedorMateria.appendChild(campoMateria);
        
        let contenedorAsunto = crear('DIV', null, 'col-xs-12 padding-pequeno-bottom');
        let marcadorAsunto = crear('B', 'Asunto: ');
        let campoAsunto = crear('SPAN', this.asunto);
        contenedorAsunto.appendChild(marcadorAsunto);
        contenedorAsunto.appendChild(campoAsunto);
        
        contenedor.appendChild(contenedorResponsable);
        contenedor.appendChild(contenedorMateria);
        contenedor.appendChild(contenedorAsunto);
        
        return contenedor;
    }
}

class ModalReservar extends DialogoModal {
    
    constructor(id, fecha, horaInicio, horaFin, idContenido, 
            materias, asuntos, nombreUsuario, fReservar) {
        
        super(id, 'Reservar', 'modal-sm', 'cabecera-reservar');
        this.fecha = fecha;
        this.horaInicio = horaInicio;
        this.horaFin = horaFin;
        this.idContenido = idContenido;
        this.materias = materias;
        this.asuntos = asuntos;
        this.nombreUsuario = nombreUsuario;
        this.fReservar = fReservar;
        this.inputAsunto = null;
        this.inputMateria = null;
    }
    
    construirCuerpo() {
        
        let contenedor = super.construirCuerpo();
        
        let contenedorAsunto = crear('DIV', null, 'col-xs-12 form-group');
        let marcadorAsunto = crear('LABEL', 'Asunto:');
        marcadorAsunto.setAttribute('for', 'input-asunto');
        this.inputAsunto = crear('SELECT', null, 'form-control', 'input-asunto');
        
        let vacioAsunto = crear('OPTION', 'Seleccione el Asunto');
        vacioAsunto.setAttribute('selected', 'selected');
        vacioAsunto.setAttribute('hidden', 'hidden');
        vacioAsunto.setAttribute('value', 'null');
        this.inputAsunto.appendChild(vacioAsunto);
        this.asuntos.forEach((asunto) => {
            let optionAsunto = crear('OPTION', asunto['asunto']);
            optionAsunto.setAttribute('value', asunto['id_asunto']);
            this.inputAsunto.appendChild(optionAsunto);
        });
        contenedorAsunto.appendChild(marcadorAsunto);
        contenedorAsunto.appendChild(this.inputAsunto);
        
        let contenedorMateria = crear('DIV', null, 'col-xs-12 form-group');
        let marcadorMateria = crear('LABEL', 'Materia:');
        marcadorMateria.setAttribute('for', 'input-materia');
        contenedorMateria.appendChild(marcadorMateria);
        this.inputMateria = crear('SELECT', null, 'form-control', 'input-materia');
        let vacioMateria = crear('OPTION', 'Seleccione la Materia');
        vacioMateria.setAttribute('value', 'null');
        vacioMateria.setAttribute('hidden', 'hidden');
        vacioMateria.setAttribute('selected', 'selected');
        this.inputMateria.appendChild(vacioMateria);
        this.materias.forEach((materia) => {
            let optionMateria = crear('OPTION', materia['nombre_materia']);
            optionMateria.setAttribute('value', materia['codigo_materia']);
            this.inputMateria.appendChild(optionMateria);
        });
        contenedorMateria.appendChild(this.inputMateria);
        
        contenedor.appendChild(contenedorAsunto);
        contenedor.appendChild(contenedorMateria);
        return contenedor;
    }
    
    getDOM() {
        
        this.DOM = super.getDOM();
        return this.DOM;
    }
    
    construirPie() {
        
        let btnReservar = crear('BUTTON', 'Reservar', 'btn btn-primary');
        
        let fecha = this.fecha;
        let horaInicio = this.horaInicio;
        let horaFin = this.horaFin;
        let idContenido = this.idContenido;
        let nombreUsuario = this.nombreUsuario;
        let fReservar = this.fReservar;
        
        btnReservar.onclick = () => {
            let materia = this.inputMateria.value;
            let asunto = this.inputAsunto.value;
            fReservar(fecha, horaInicio, horaFin, materia, asunto, 
                        idContenido, nombreUsuario);
            cerrarModal(this.DOM);
        };
        let contenedor = super.construirPie();
        contenedor.appendChild(btnReservar);
        return contenedor;
    }
}

class Evento {

    constructor(inicio, fin, titulo, descripcion) {

        if (esNulo(inicio) || esNulo(fin)) throw 'Inicio o Fin no pueden ser nulos';
        if (inicio > fin) throw 'Inicio no puede ser menor que fin';
        this.inicio = inicio;
        this.fin = fin;
        this.titulo = titulo;
        this.descripcion = descripcion;
        this.accion = this.accion.bind(this);
    }
    
    contiene(fechaInicio, fechaFin) {
        
        return fechaInicio <= this.inicio && this.fin < fechaFin ||
                fechaInicio <= this.inicio && this.inicio < fechaFin ||
                fechaInicio < this.fin && this.fin <= fechaFin ||
                this.inicio < fechaInicio && fechaFin <= this.fin;
    }
    
    accion(domEvento) {
        
        console.log('Evento sin accion');
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = 'transparent';
        dom.style.cursor = 'default';
    }
    
    permiteReserva() {
      
        throw 'Evento.permiteReserva debe ser sobreescrito';
    }
    
    esImposibilitadorReserva() {
        
        throw 'Evento.esImposibilitadorReserva debe ser sobreescrito';
    }
    
    esEventoDebil() {
        
        throw 'Evento.esEventoDebil debe ser sobreescrito';
    }
}

class EventoRaiz extends Evento {
    
    constructor() {
        
        super(INICIO_TIEMPOS, FIN_TIEMPOS, 'raiz');
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoCronograma extends Evento {

    constructor(inicio, fin, anio, gestion) {

        super(inicio, fin, 'cronograma');
        this.anio = anio;
        this.gestion = gestion;
    }
    
    accion() {
        // invalida el evento
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_CRONOGRAMA;
        dom.style.cursor = 'not-allowed';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoInvalidador extends Evento {
    
    constructor(inicio, fin) {
        
        super(inicio, fin);
    }
    
    accion() {
        // Invalida el evento
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_INVALIDADOR;
        dom.style.cursor = 'not-allowed';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return true;
    }
}

class EventoPermiteReserva extends Evento {
    
    constructor(inicio, fin, titulo, descripcion, idContenido) {
        
        super(inicio, fin, titulo, descripcion);
        this.setDOM = this.setDOM.bind(this);
        this.idContenido = idContenido;
    }
    
    getIdContenido() {
        
        return this.idContenido;
    }
    
    accion(domEvento) { 
        // Otra accion es llamada en este caso
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_PERMITE_RESERVA;
        dom.style.cursor = 'pointer';
    }
    
    permiteReserva() {
        
        return true;
    }
    
    esImposibilitadorReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoCierreUniversidad extends Evento {
    
    constructor(inicio, fin, titulo, descripcion) {
        
        super(inicio, fin, titulo, descripcion);
    }
    
    accion() {
        // invalida el evento
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_CIERRE;
        dom.style.cursor = 'not-allowed';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return true;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoTolerancia extends Evento {
    
    constructor(inicio, fin, descripcion) {
        
        super(inicio, fin, 'Tolerancia', descripcion);
    }
    
    accion(domEvento) { // Otra accion puede llamarse si permite reservas
        
        let modal = new ModalContenido('modal-tolerancia',
                this.inicio, this.fin, this.titulo, this.descripcion);
        abrirModal(modal.getDOM());
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_TOLERANCIA;
        dom.style.cursor = 'pointer';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoOtro extends Evento {
    
    constructor(inicio, fin, titulo, descripcion) {
        
        super(inicio, fin, titulo, descripcion);
    }
    
    accion(domEvento) { // Otra accion puede llamarse si permite reservas
        
        let modal = new ModalContenido('modal-otro',
                this.inicio, this.fin, this.titulo, this.descripcion);
        abrirModal(modal.getDOM());
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_OTRO;
        dom.style.cursor = 'pointer';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() {
        
        return false;
    }
    
    esEventoDebil() {
        
        return true;
    }
    
}

class EventoReservaAcademica extends Evento {
    
    constructor(inicio, fin, evento, responsable, materia, asunto) {
        
        super(inicio, fin, evento);
        this.responsable = responsable;
        this.materia = materia;
        this.asunto = asunto;
    }
    
    accion(domEvento) {
        
        let modal = new ModalVerReservaAcademica('modal-reserva', 
                    this.inicio, this.fin, this.titulo, this.responsable, 
                    this.materia, this.asunto);
        abrirModal(modal.getDOM());
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_RESERVA;
        dom.style.cursor = 'pointer';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() { 
        
        return true;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class EventoReservaSolicitada extends Evento {
    
    constructor(inicio, fin, evento, responsable, 
            institucion, descripcion) {
        
        super(inicio, fin, evento);
        this.responsable = responsable;
        this.institucion = institucion;
        this.descripcion = descripcion;
    }
    
    accion(domEvento) {
        
        let modal = new ModalVerReservaSolicitada('modal-reserva-solicitada', 
                    this.inicio, this.fin, this.titulo, this.responsable, 
                    this.institucion, this.descripcion);
        abrirModal(modal.getDOM());
    }
    
    setDOM(dom) {
        
        dom.style.backgroundColor = COLOR_EVENTO_RESERVA;
        dom.style.cursor = 'pointer';
    }
    
    permiteReserva() {
        
        return false;
    }
    
    esImposibilitadorReserva() { 
        
        return true;
    }
    
    esEventoDebil() {
        
        return false;
    }
}

class NodoEvento {

    constructor(evento) {

        if (esNulo(evento)) {
            throw 'El evento no puede ser nulo';
        }
        this.hijos = [];
        this.evento = evento;
    }

    limiteIzq() {

        return this.evento.inicio;
    }

    limiteDer() {

        return this.evento.fin;
    }

    estaVacio() {

        return esNulo(this.evento) && this.hijos.length === 0;
    }

    puedeAnadir(nodo) {

        return this.tieneDentro(nodo.limiteIzq(), nodo.limiteDer());
    }
    
    puedeAnadirParcialmente(nodo) {
        
        return nodo.estaDentroFechasIzq(this.limiteIzq(), this.limiteDer()) && 
                nodo.limiteDer() < this.limiteDer() ||
                nodo.estaDentroFechasDer(this.limiteIzq(), this.limiteDer()) && 
                nodo.limiteIzq() > this.limiteIzq();
    }

    anadir(evento) {

        let nodo = new NodoEvento(evento);
        return this.anadirNodo(nodo);
    }

    anadirNodo(nodo) {

        if (this.puedeAnadir(nodo)) {
            for (let i = 0; i < this.hijos.length; i++) {
                let hijo = this.hijos[i];
                if (hijo.anadirNodo(nodo)) {
                    return true;
                }
            }
            this.recortarHijos(nodo);
            this.hijos.push(nodo);
            return true;
        }
        return false;
    }

    recortarHijos(ramaDestino) {

        if (this.dentroLimites(ramaDestino)) {
            for (let i = 0; i < this.hijos.length; i++) {
                let hijo = this.hijos[i];
                if (ramaDestino.anadirNodo(hijo)) {
                    this.hijos = remover(this.hijos, i);
                    i--;
                } else {
                    hijo.recortarHijos(ramaDestino);
                }
            }
        }
    }

    dentroLimites(nodo) {

        return nodo.estaDentroFechasIzq(this.limiteIzq(), this.limiteDer()) || 
                nodo.estaDentroFechasDer(this.limiteIzq(), this.limiteDer()) && 
                !nodo.estaDentroFechas(this.limiteIzq(), this.limiteDer());
    }
    
    estaDentroFechas(fechaInicio, fechaFin) {
        
        return fechaInicio <= this.limiteIzq() && this.limiteDer() <= fechaFin;
    }
    
    estaDentroFechasIzq(fechaInicio, fechaFin) {
        
        return fechaInicio <= this.limiteIzq() && this.limiteIzq() <= fechaFin;
    }
    
    estaDentroFechasDer(fechaInicio, fechaFin) {
        
        return fechaInicio <= this.limiteDer() && this.limiteDer() <= fechaFin;
    }
    
    tieneDentro(fechaInicio, fechaFin) {
        
        return this.limiteIzq() <= fechaInicio && fechaFin <= this.limiteDer();
    }
    
    obtener(fechaInicio, fechaFin) {
        
        if (this.estaDentroFechas(fechaInicio, fechaFin) ||
                this.estaDentroFechasDer(fechaInicio, fechaFin) ||
                this.estaDentroFechasIzq(fechaInicio, fechaFin) ||
                this.tieneDentro(fechaInicio, fechaFin)) {
            let resultado = [this.evento];
            this.hijos
                    .map((hijo) => hijo.obtener(fechaInicio, fechaFin))
                    .forEach((eventos)=> resultado = resultado.concat(eventos));
            return resultado;
        }
        return [];
    }
}

class Observador { // Interfaz
    
    actualizar(datos) {
        
        throw 'Operacion no soportada';
    }
}

class Observable {
    
    constructor() {
        
        this.observadores = [];
    }
    
    anadirObservador(observador) {

        this.observadores.push(observador);
    }
    
    notificarObservadores(datos) {
        
        this.observadores.forEach((observador) => observador.actualizar(datos));
    }
}

class BarraNavegacion extends Observable {
    
    constructor() {
        super();
        this.fechaInicio = null;
        this.fechaFin = null;
        this.campoTexto = null;
        
        this.moverDerecha = this.moverDerecha.bind(this);
        this.moverIzquierda = this.moverIzquierda.bind(this);
    }
    
    actualizarTextoFechas() {
        
        this.campoTexto.innerHTML = "Desde <b>" + 
                this.formatearFecha(this.fechaInicio) + 
                "</b> - Hasta <b>" + this.formatearFecha(this.fechaFin) + "</b>";

        this.notificarObservadores({
            fechaInicio: this.fechaInicio, 
            fechaFin: this.fechaFin

        });
    }


    formatearFecha(fecha) {
        
        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        var anio = fecha.getFullYear();
        var formateado = rellenar(dia) + " de " + 
                mesLiteral(mes) + " del " + 
                rellenar(anio, 4);
        return formateado;
    }
    
    setPosicion(fecha) {
        
        if (this.campoTexto === null) throw 'El campo de texto no puede ser nulo';
        let diaFecha = fecha.getDay() || 1;
        
    
        this.fechaInicio = new Date(fecha.getTime() - dias2Milisecs(diaFecha - 1));
        this.fechaFin = new Date(fecha.getTime() + dias2Milisecs(6 - diaFecha));
        this.fechaInicio.setHours(0);
        this.fechaInicio.setMinutes(0);
        this.fechaInicio.setSeconds(0);
        this.fechaInicio.setMilliseconds(0);
        this.fechaFin.setHours(23);
        this.fechaFin.setMinutes(59);
        this.fechaFin.setSeconds(59);
        this.fechaFin.setMilliseconds(0);
        this.actualizarTextoFechas();
    }
    
    moverIzquierda() {
        
        this.fechaInicio = new Date(this.fechaInicio.getTime() - dias2Milisecs(7));
        this.fechaFin = new Date(this.fechaFin.getTime() - dias2Milisecs(7));
        this.actualizarTextoFechas();
    }
    
    moverDerecha() {
        
        this.fechaInicio = new Date(this.fechaInicio.getTime() + dias2Milisecs(7));
        this.fechaFin = new Date(this.fechaFin.getTime() + dias2Milisecs(7));
        this.actualizarTextoFechas();
    }
    
    crearIndicador(indicador, color) {
        
        let contenedor = crear('DIV');
        contenedor.style.marginRight = '5px';
        contenedor.style.marginLeft = '10px';
        contenedor.style.display = 'inline';
        let caja = crear('DIV');
        caja.style.border = '1px solid #AAA';
        caja.style.marginRight = '5px';
        caja.style.display = 'inline-block';
        caja.style.width = '18px';
        caja.style.height = '18px';
        caja.style.backgroundColor = color;
        caja.style.paddingTop = '8px';
        let span = crear('SPAN', indicador);
        
        contenedor.appendChild(caja);
        contenedor.appendChild(span);
        
        return contenedor;
    }

    compararFechaActual(fechaIniCronograma,fechaFinCronograma){
        var diaActual = new Date();
        
        console.log(diaActual);
        if(fechaFinCronograma < diaActual || fechaIniCronograma > diaActual){
            diaActual = fechaIniCronograma;
        }
        console.log(diaActual);
        return diaActual;
    }
//////////////////
    cambiarFechaADate(cadenayymmdd){
        var aux = cadenayymmdd.split("-");
        var res = aux[2].split(" ");
        var listaRes = [parseInt(aux[0]),parseInt(aux[1]),parseInt(res[0])];
        var fecha = new Date(listaRes[0],listaRes[1],listaRes[2],0,0,0)
        return fecha;
    }
    
    getDOM(fechaI, fechaF) {
        
        let contenedorBarra = crear('DIV', null, 'row padding-pequeno-bottom');
        let contenedorIzq = crear('DIV', null, 'col-md-1');
        let botonIzquierda = crear('BUTTON', '&lt;', 'btn btn-default');
        botonIzquierda.onclick = this.moverIzquierda;
        contenedorIzq.appendChild(botonIzquierda);
        
        let contenedorCentro = crear('DIV', null, 'col-md-12');
        let texto = crear('H4', null);
        texto.style.marginTop = '20px';
        this.campoTexto = texto;
        var fechIni = this.cambiarFechaADate(fechaI);
        var fechFi = this.cambiarFechaADate(fechaF);
        var diaInicio = this.compararFechaActual(fechIni, fechFi);
        this.setPosicion(diaInicio);

        contenedorCentro.appendChild(texto);
        let contenedorDer = crear('DIV', null, 'col-md-11 text-right');
        contenedorDer.appendChild(this.crearIndicador('Cronograma', COLOR_CRONOGRAMA));
        contenedorDer.appendChild(this.crearIndicador('Reserva', COLOR_EVENTO_RESERVA));
        contenedorDer.appendChild(this.crearIndicador('Permite reserva', COLOR_EVENTO_PERMITE_RESERVA));
        contenedorDer.appendChild(this.crearIndicador('Cierre universidad', COLOR_EVENTO_CIERRE));
        contenedorDer.appendChild(this.crearIndicador('Tolerancia', COLOR_EVENTO_TOLERANCIA));
        contenedorDer.appendChild(this.crearIndicador('Otro', COLOR_EVENTO_OTRO));
        contenedorDer.appendChild(this.crearIndicador('Inválido', COLOR_EVENTO_INVALIDADOR));
        let botonDerecha = crear('BUTTON', '&gt;', 'btn btn-default');
        botonDerecha.onclick = this.moverDerecha;
        contenedorDer.appendChild(botonDerecha);
        
        contenedorBarra.appendChild(contenedorCentro);
        contenedorBarra.appendChild(contenedorIzq);
        contenedorBarra.appendChild(contenedorDer);
        
        return contenedorBarra;
    }
    
}

class FilaFechas extends Observador {
    
    constructor() {
        
        super();
        this.camposFecha = [];
    }
    
    actualizar(datos) {
        
        let inicio = datos.fechaInicio.getTime();
        for (let dia = 0; dia < NUMERO_DIAS_SEMANA; dia++) {
            let fecha = new Date(inicio + dias2Milisecs(dia));
            this.camposFecha[dia].innerHTML = this.formatearFecha(fecha);
        }
    }
    
    formatearFecha(fecha) {
        
        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        return mesLiteral(mes) + ' ' + rellenar(dia);
    }
    
    getDOM() {
        
        let fila = crear('TR');
        for (let indice = 0; indice < NUMERO_DIAS_SEMANA; indice++) {
            let campoFecha = crear('TD');
            this.camposFecha.push(campoFecha);
            fila.appendChild(campoFecha);
        }
        return fila;
    }
}

class Calendario extends Observador {

    constructor(cronograma, utilesReserva) {

        super();
        if (esNulo(cronograma)) throw 'El cronograma no puede ser nulo';
        this.cronograma = cronograma;
        this.utilesReserva = utilesReserva;
        this.barraNavegacion = new BarraNavegacion();
        this.filaFechas = new FilaFechas();
        this.barraNavegacion.anadirObservador(this);
        this.barraNavegacion.anadirObservador(this.filaFechas);
        this.raiz = new NodoEvento(new EventoRaiz());
        let eventoCronograma = new EventoCronograma(
                parseFechaHora(this.getInicio()), 
                parseFechaHora(this.getFin()), 
                cronograma.anio, 
                cronograma.gestion);
        this.raiz.anadir(eventoCronograma);
        this.raiz.anadir(new EventoInvalidador(INICIO_TIEMPOS, parseFechaHora(this.getInicio())));
        this.raiz.anadir(new EventoInvalidador(parseFechaHora(this.getFin()), FIN_TIEMPOS));
        let duracion = getMinutos(this.getDuracionPeriodo());
        let horaInicio = getMinutos(this.getHoraInicioJornada());
        let horaFin = getMinutos(this.getHoraFinJornada());
        let numeroPeriodos = Math.floor((horaFin - horaInicio)/duracion);
        
        this.periodos = [];
        let horaActual = horaInicio;
        for (let i = 0; i< numeroPeriodos; i++) {
            this.periodos.push({
                horaInicio: horaActual, 
                horaFin: horaActual + duracion
            });
            horaActual += duracion;
        }
        this.celdas = Array(numeroPeriodos).fill(null).map(() => Array(NUMERO_DIAS_SEMANA));
        
        this.celdaPresionada = this.celdaPresionada.bind(this);
        this.accionReservar = this.accionReservar.bind(this);
        this.compararEvento = this.compararEvento.bind(this);
    }
    
    getInicio() {
        
        return this.cronograma.inicio;
    }
    
    getFin() {
        
        return this.cronograma.fin;
    }
    
    getHoraInicioJornada() {
        
        return this.cronograma.configuracion.horaInicioJornada;
    }
    
    getHoraFinJornada() {
        
        return this.cronograma.configuracion.horaFinJornada;
    }
    
    getDuracionPeriodo() {
        
        return this.cronograma.configuracion.duracionPeriodo;
    }
    
    getHoraFinSabado() {
        
        return this.cronograma.configuracion.horaFinSabado;
    }
    
    celdaPresionada(evento) {
        
        let origen = evento.target;
        console.log(origen.getFil() + ", " + origen.getCol());
    }

    getDOM() {
        let tabla = crear('TABLE', null, 'table table-bordered ');
        
        let cabecera = crear('THEAD');
        let fila = crear('TR');
        let thPeriodo = crear('TH', 'Periodo', 'id-td');
        thPeriodo.style.verticalAlign = 'middle';
        thPeriodo.setAttribute('rowspan', '2');
        fila.appendChild(thPeriodo);
        fila.appendChild(crear('TH', 'Lunes'));
        fila.appendChild(crear('TH', 'Martes'));
        fila.appendChild(crear('TH', 'Miercoles'));
        fila.appendChild(crear('TH', 'Jueves'));
        fila.appendChild(crear('TH', 'Viernes'));
        fila.appendChild(crear('TH', 'Sábado'));
        
        cabecera.appendChild(fila);
        cabecera.appendChild(this.filaFechas.getDOM());
        
        let cuerpo = crear('TBODY');
        for (let fil = 0; fil< this.periodos.length; fil++) {
            let periodo = this.periodos[fil];
            fila = crear('TR');
            fila.appendChild(crear('TD', 
                    invMinutos(periodo.horaInicio) + ' - ' + 
                    invMinutos(periodo.horaFin), 'periodo', 'id-td'));
            for (let col = 0; col< NUMERO_DIAS_SEMANA; col++) {
                let celda = crear('TD', null, 'celda');
                let boton = crear('BUTTON', null, 'cuadrado');
                boton.getFil = () => {return fil;};
                boton.getCol = () => {return col;};
                celda.appendChild(boton);
                fila.appendChild(celda);
                this.celdas[fil][col] = boton;
            }
            cuerpo.appendChild(fila);
        }
        tabla.appendChild(cabecera);
        tabla.appendChild(cuerpo);
        
        let contenedor = crear('DIV', null, 'contenedor-calendario ');
        contenedor.appendChild(this.barraNavegacion.getDOM(this.cronograma.inicio, this.cronograma.fin));///////asdasdsad//////
        let contenedorTabla = crear('DIV', null, 'table-responsive');
        contenedorTabla.appendChild(tabla);
        contenedor.appendChild(contenedorTabla);
        return contenedor;
    }
    
    obtenerPeriodoComoFecha(periodo, dia, fechaBase) {
        
        let inicioMinutos = periodo.horaInicio;
        let finMinutos = periodo.horaFin;
        let inicioPeriodo = new Date(fechaBase.getTime());
        let nuevoDia = fechaBase.getDate() + dia;
        inicioPeriodo.setDate(nuevoDia);
        inicioPeriodo.setHours(Math.floor(inicioMinutos / 60));
        inicioPeriodo.setMinutes(inicioMinutos - Math.floor(inicioMinutos / 60) * 60);
        inicioPeriodo.setSeconds(0);
        inicioPeriodo.setMilliseconds(0);
        let finPeriodo = new Date(fechaBase.getTime());
        finPeriodo.setDate(nuevoDia);
        finPeriodo.setHours(Math.floor(finMinutos / 60));
        finPeriodo.setMinutes(finMinutos - Math.floor(finMinutos / 60) * 60);
        finPeriodo.setSeconds(0);
        finPeriodo.setMilliseconds(0);
        
        return {
            inicioPeriodo: inicioPeriodo,
            finPeriodo: finPeriodo
        };
    }
    
    crearInvalidadorSabado(fechaSabado) {
        
        let anio = fechaSabado.getFullYear();
        let mes = fechaSabado.getMonth();
        let dia = fechaSabado.getDate();
        let finSabado = getMinutos(this.getHoraFinSabado());
        let horas = Math.floor(finSabado / 60);
        let minutos = ((finSabado/60) - horas) * 60;
        let inicio = new Date(anio, mes, dia, horas, minutos, 0, 0);
        let fin = new Date(anio, mes, dia, 23, 59, 0, 0);
        return new EventoInvalidador(inicio, fin);
    }
    
    actualizar(datos) {
        
        let fechaInicio = datos.fechaInicio;
        let fechaFin = datos.fechaFin;
        let eventos = this.raiz.obtener(fechaInicio, fechaFin);
        eventos.push(this.crearInvalidadorSabado(fechaFin));
        eventos.sort(this.compararEvento);
        for (let dia = 0; dia < NUMERO_DIAS_SEMANA; dia++) {
            for (let periodo = 0; periodo < this.periodos.length; periodo++) {
                let celda = this.celdas[periodo][dia];
                let periodosFecha = this.obtenerPeriodoComoFecha(
                        this.periodos[periodo], dia, fechaInicio);
                let inicioPeriodo = periodosFecha.inicioPeriodo;
                let finPeriodo = periodosFecha.finPeriodo;
                for (let indice = 0; indice < eventos.length; indice ++) {
                    let evento = eventos[indice];
                    if (evento.contiene(inicioPeriodo, finPeriodo)) {
                        celda.onclick = evento.accion;
                        evento.setDOM(celda);
                        for (; indice < eventos.length; indice ++) {
                            let sigEvento = eventos[indice];
                            if (sigEvento.esImposibilitadorReserva()) {
                                break;
                            }
                            else if (sigEvento.permiteReserva()){
                                if (!evento.esImposibilitadorReserva()) {
                                    celda.onclick = (e) => {
                                        e.getOriginal = () => {
                                            return sigEvento;
                                        };
                                        return this.accionReservar(e);
                                    };
                                    if (evento.esEventoDebil()) {
                                        sigEvento.setDOM(celda);
                                    }
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }
    }
    
    valorarEvento(evento) {
        
        if (evento instanceof EventoReservaAcademica || 
                evento instanceof EventoReservaSolicitada ) {
            return 0;
        }
        else if (evento  instanceof EventoInvalidador) {
            return 1;
        }
        else if (evento instanceof EventoCierreUniversidad) {
            return 2;
        }
        else if (evento instanceof EventoTolerancia) {
            return 3;
        }
        else if (evento instanceof EventoPermiteReserva) {
            return 4;
        }
        else if (evento instanceof EventoOtro) {
            return 5;
        }
        else if (evento instanceof EventoCronograma){
            return 6;
        }
        else {
            return 7;
        }
    }
    
    compararEvento(unEvento, otroEvento) {
        
        return this.valorarEvento(unEvento) - this.valorarEvento(otroEvento);
    }
    
    accionReservar(evento) {
        
        // el evento original debe proporcionar su id contenido añadiendolo al evento
        let original = evento.getOriginal(); 
        let idContenido = original.getIdContenido();
        
        let indicePeriodo = evento.target.getFil();
        let diaSemana = evento.target.getCol(); // 0 lunes ,.., 6 sabado
        let periodo = this.periodos[indicePeriodo];
        let lunes = this.barraNavegacion.fechaInicio;
        let fecha = new Date(lunes.getTime() + dias2Milisecs(diaSemana));
        let horaInicio = invMinutos(periodo['horaInicio']);
        let horaFin = invMinutos(periodo['horaFin']);
        
        let ahora = new Date();
        let periodoComoFecha = this.obtenerPeriodoComoFecha(periodo, 0, fecha);
        let utiles = this.utilesReserva;
        if (ahora < periodoComoFecha.inicioPeriodo) {
            let modal = new ModalReservar('modal-reservar', fecha, 
                                horaInicio, horaFin, idContenido, 
                                utiles['materias'], utiles['asuntos'], 
                                utiles['nombre_usuario'], this.fReservar);
            abrirModal(modal.getDOM());
        }
        else {
            let modal = new ModalInformativo(
                    'modal-informativo', 'modal-sm', 'cabecera-no-puede-reservar', 
                    'No puede crear reserva', 
                    'No puede reservar en fechas y/o periodos pasados');
            abrirModal(modal.getDOM());
        }
    }
    
    setAccionReservar(fReservar) {
        
        this.fReservar = fReservar;
    }
    
    forzarActualizar() {
        
        this.actualizar({
            fechaInicio: this.barraNavegacion.fechaInicio,
            fechaFin: this.barraNavegacion.fechaFin
        });
    }
    
    anadirEvento(evento) {
        
        return this.raiz.anadir(evento);
    }
}
