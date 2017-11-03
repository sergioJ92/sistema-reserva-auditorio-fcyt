--SET NAMES 'latin9';

INSERT INTO cronograma_academico (anio, gestion, fecha_hora_inicio, fecha_hora_fin, fecha_activacion) VALUES
(2017, '1', '2017-06-15 08:53:00', '2017-09-30 08:53:00', '2017-06-07'),
(2017, '2', '2017-06-14 09:37:00', '2017-07-09 09:37:00', '2017-05-30');

INSERT INTO contenido (fecha_hora_inicio, fecha_hora_fin, descripcion, anio, gestion) VALUES
('2017-06-17 10:07:00', '2017-06-28 10:07:00', 'periodo de toma de examenes de primer parcial', 2017, '1'),
('2017-06-30 14:00:00', '2017-06-30 21:45:00', 'dia de la Autonomia', 2017, '1'),
('2017-08-06 00:00:00', '2017-08-06 23:59:00', 'Dia de la fundacion de Bolivia', 2017, '1'),
('2017-06-23 09:00:00', '2017-07-07 14:00:00', 'Piema opcion de examen de ingreso gestion 2 2017', 2017, '1'),
('2017-06-30 00:00:00', '2017-07-14 21:45:00', 'Periodo de examens de segundo parcial', 2017, '1'),
('2017-06-18 01:23:00', '2017-07-01 01:23:00', '', 2017, '2'),
('2017-06-21 09:25:00', '2017-06-21 19:25:00', '', 2017, '2'),
('2017-06-23 08:26:00', '2017-06-23 18:26:00', '', 2017, '2'),
('2017-06-22 08:27:00', '2017-06-22 20:27:00', '', 2017, '2');


INSERT INTO actividad (id_contenido, titulo, permite_reserva) VALUES
(1, 'primero parciales', 0),
(4, 'examen de Ingreso', 0),
(5, 'Segundo Parcial', 1),
(6, 'Parciales', 1);


INSERT INTO asunto (asunto) VALUES
('Examen'),
('Clases'),
('Ninguno');

INSERT INTO configuracion (anio, gestion, duracion_periodo, hora_inicio_jornada, hora_fin_jornada, hora_fin_sabado) VALUES
(2017, '1', '1:30', '6:45', '21:45', '12:45'),
(2017, '2', '1:0', '6:45', '21:45', '12:45');


INSERT INTO rol (nombre_rol, puede_tener_materias) VALUES
('Administrador', 0),
('Docente', 1),
('secretaria', 0),
('Super usuario', 1);

INSERT INTO usuario (nombre_usuario, contrasenia, nombres, apellidos, nombre_rol) VALUES
('root', '$2y$10$L1akpmxVFSI7/1/cwToZvegUJyrtYjAXsgAkUgMwTsaP5YppYnNxe', 'Evelyn', 'Cusi Lopez', 'Super usuario');

INSERT INTO correo_usuario (nombre_usuario, correo) VALUES
('root', 'cusi.evelyn@gmail.com');



INSERT INTO fechas_nacionales ( titulo, dia, mes, feriado) VALUES
('Año nuevo', 1, 1, 1),
('Dia de la Fundación del Estado', 22, 1, 1),
('Carnaval', 27, 2, 1),
('Carnaval', 28, 3, 1),
('Viernes santo', 14, 4, 1),
('Día del trabajador', 1, 5, 1),
('Corpus Christi', 15, 6, 1),
('Año nuevo Aymara', 21, 7, 1),
('Día de la independencia de Bolivia', 6, 8, 1),
('Día de todos los santos', 2, 11, 1),
('Navidad', 25, 12, 1),
('Día del padre', 19, 3, 0),
('Día del mar', 23, 3, 0),
('Día del niño', 12, 4, 0),
('Día de la madre', 27, 6, 0),
('San Juan', 24, 6, 0),
('Día de la bandera boliviana', 17, 8, 0),
('Día de la mujer boliviana', 11, 10, 0),
('Día del departamento de Oruro', 10, 11, 0),
('Día del departamento de Tarija', 15, 4, 0),
('Día del departamento de Chuquisaca', 25, 5, 0),
('Día del departamento de La Paz', 16, 7, 0),
('Día del departamento de Cochabamba', 14, 9, 1),
('Día del departamento de Santa Cruz', 24, 9, 0),
('Día del departamento de Pando', 11, 10, 0),
('Día del departamento de Potosi', 10, 11, 0),
('Día del departamento de Beni', 18, 11, 0);


INSERT INTO feriado_especial (id_contenido, titulo) VALUES
(3, 'Dia de la patria'),
(8, 'Feriado');


INSERT INTO materia (codigo_materia, nombre_materia) VALUES
(0, 'Ninguno'),
(1803001, 'Ingles I'),
(2010010, 'Introducción a la programación'),
(2010013, 'Arquitectura de Computadoras 1'),
(2010015, 'Base de datos 1'),
(2010022, 'Sistemas de información 2'),
(2010024, 'Taller de Ingenieria de Software'),
(2010037, 'Teoria de Grafos'),
(2010202, 'Inteligencia Artificial 2');


INSERT INTO otro (id_contenido, titulo, cierre_universidad) VALUES
(9, 'Otro', 0);


INSERT INTO privilegio (nombre_privilegio, nombre_rol) VALUES
('Cronograma', 'Administrador'),
('Cronograma', 'secretaria'),
('Cronograma', 'Super usuario'),
('Reservas', 'Docente'),
('Reservas', 'secretaria'),
('Reservas', 'Super usuario'),
('Solicitudes', 'Administrador'),
('Solicitudes', 'secretaria'),
('Solicitudes', 'Super usuario'),
('Usuarios', 'Administrador'),
('Usuarios', 'Super usuario');


INSERT INTO reserva (fecha, hora_inicio, hora_fin, evento) VALUES
('2017-07-12', '08:15', '09:45', 'Reserva'),
('2017-07-07', '08:15', '09:45', 'Reserva'),
('2017-07-08', '11:15', '12:45', 'Reserva'),
('2017-06-30', '12:45', '14:15', 'Reserva'),
('2017-06-30', '17:15', '18:45', 'Reserva'),
('2017-07-01', '11:15', '12:45', 'Reserva');



INSERT INTO reserva_academica (id_reserva, codigo_materia, id_asunto, id_contenido) VALUES
(1, 2010022, 2, 5),
(2, 2010022, 2, 5),
(3, 2010010, 2, 5),
(4, 2010022, 1, 5),
(5, 2010010, 2, 5),
(6, 2010024, 2, 5);


INSERT INTO responsable_reserva (nombre_usuario, id_reserva) VALUES
('root', 1),
('root', 2),
('root', 3),
('root', 4),
('root', 5),
('root', 6);



INSERT INTO solicitud_reserva (leido, fecha, hora_inicio, hora_fin, responsable, institucion, evento, descripcion) VALUES
(1, '2017-06-20', '6:45', '8:15', 'Mark', 'Jala Soft', 'TDD', 'expo'),
(0, '2017-06-23', '10:00', '17:00', 'Leticia Blanco', 'UMSS', 'obi', 'olimpiadas'),
(0, '2017-06-22', '20:30', '21:50', 'Juan', 'Laboratorio', 'Curso de mantenimiento', '');


INSERT INTO telefono (id_solicitud_reserva, telefono1) VALUES
(1, 70700865),
(2, 70700001),
(3, 4345754);


INSERT INTO telefono_usuario (nombre_usuario, telefono) VALUES
('root', 70700865);


INSERT INTO tiene_materia (nombre_usuario, codigo_materia) VALUES
('root', 2010010),
('root', 2010022),
('root', 2010024);

INSERT INTO tolerancia (id_contenido) VALUES
(2),
(7);


INSERT INTO correo (id_solicitud_reserva, correo1) VALUES
(1, 'cusi.evelyn@gmail.com'),
(2, 'leticia@correo.com'),
(3, 'owneo@live.com');

update cronograma_academico set fecha_hora_fin = '2017-12-22 09:37:00' where anio=2017 and gestion='2';