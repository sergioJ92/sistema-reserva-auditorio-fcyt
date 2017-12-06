

<body>
    <?php include RAIZ . '/navegacion.inc'; ?>
    <div class="container">
        <div class="row padding-pequeno sin-padding-bottom">
            <div class="col-md-12">
                <h3 class="inline">Reservas</h3>
            </div>
            <div  class="col-md-4 form-group" id="edificio">
                <label>Seleccionar Edificio <span class="rojo">*</span></label>
                <select name="selEdificio" class="form-control" id="selEdificio">
                    <option selected="" value="null" hidden="">Nombre Edificio</option>
                    <?php array_map(crearOption, SolicitudReserva::obtenerTodosLosEdificios()); ?>

                </select>
            </div>
            <div  class="col-md-4 form-group" id="piso">
                <label>Seleccionar Piso <span class="rojo">*</span></label>
                <select name="selPiso" class="form-control" id="selPiso">
                </select>
            </div>
            <div  class="col-md-4 form-group" id="aula">
                <label>Seleccionar Aula <span class="rojo">*</span></label>
                <select name="selAula" class="form-control" id="selAula">
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="selAnioGestion">
                    <span>Seleccione el Cronograma Académico</span>
                </label>
                <select id="selAnioGestion" class="form-control" onchange="cuandoCambiaAnioGestion()">
                    <option selected="" hidden="" value="null">Año y Gestion</option>
                    <?php array_map(crearOption, CronogramaAcademico::obtenerCronogramasActivados()); ?>
                </select>
            </div>
        </div>
        <hr class="separador-pequeno">
        <div id="contenedor-msg"></div>
        <div id="calendario">
            <div class="alert alert-info">
                <label for="selAnioGestion">Seleccione un Cronograma Académico y un Aula</label> para visualizar su contenido en el Calendario
            </div>
        </div>
    </div>
    <link href="calendario.css" type="text/css" rel="stylesheet">
    <script src="calendario.js"></script>
    <script src="reservas-aula/reservas.js"></script>
    <?php include RAIZ . '/pie.inc'; ?>
</body>