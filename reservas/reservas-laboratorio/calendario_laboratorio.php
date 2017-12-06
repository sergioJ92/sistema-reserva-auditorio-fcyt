

<body>  
    <?php include RAIZ . '/navegacion.inc'; ?>
    <div class="container">
        <div class="row padding-pequeno sin-padding-bottom">
            <div class="col-md-12">
                <h3 class="inline">Reservas</h3>
            </div>
            
            <div  class="col-md-6 form-group" id="departamento">
                <label>Seleccionar Departamento <span class="rojo">*</span></label>
                <select name="selDepartamento" class="form-control" id="selDepartamento">
                    <option selected="" value="null" hidden="">Nombre Departamento</option>
                    <?php array_map(crearOption, SolicitudReserva::obtenerTodosLosDepartamentos()); ?>

                </select>
            </div>
            <div  class="col-md-6 form-group" id="laboratorio">
                <label>Seleccionar Laboratorio <span class="rojo">*</span></label>
                <select name="selLaboratorio" class="form-control" id="selLaboratorio">
                </select>
            </div>

            <div class="col-md-6 form-group">
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
                <label for="selAnioGestion">Seleccione un Cronograma Académico y un Laboratorio</label> para visualizar su contenido en el Calendario
            </div>
        </div>
    </div>
    <link href="calendario.css" type="text/css" rel="stylesheet">
    <script src="calendario.js"></script>
    <script src="reservas-laboratorio/reservas.js"></script>
    <?php include RAIZ . '/pie.inc'; ?>
</body>