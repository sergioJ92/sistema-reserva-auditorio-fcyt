/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     24/10/2017 12:10:38                          */
/*==============================================================*/


drop index ACTIVIDAD_PK;

drop table ACTIVIDAD;

drop index ASUNTO_PK;

drop table ASUNTO;

drop index CONFIGURACION_PK;

drop table CONFIGURACION;

drop index TIENE_FK;

drop index CONTENIDO_PK;

drop table CONTENIDO;

drop index RELATIONSHIP_16_FK;

drop index CORREO_PK;

drop table CORREO;

drop index RELATIONSHIP_19_FK;

drop index CORREO_USUARIO_PK;

drop table CORREO_USUARIO;

drop index CRONOGRAMA_ACADEMICO_PK;

drop table CRONOGRAMA_ACADEMICO;

drop index FECHAS_NACIONALES_PK;

drop table FECHAS_NACIONALES;

drop index FERIADO_ESPECIAL_PK;

drop table FERIADO_ESPECIAL;

drop index MATERIA_PK;

drop table MATERIA;

drop index OTRO_PK;

drop table OTRO;

drop index RELATIONSHIP_18_FK;

drop index PRIVILEGIO_PK;

drop table PRIVILEGIO;

drop index RESERVA_PK;

drop table RESERVA;

drop index RELATIONSHIP_26_FK;

drop index RELATIONSHIP_10_FK;

drop index RELATIONSHIP_9_FK;

drop index RESERVA_ACADEMICA_PK;

drop table RESERVA_ACADEMICA;

drop index RESERVA_LOG_PK;

drop table RESERVA_LOG;

drop index RESERVA_SOLICITADA_PK;

drop table RESERVA_SOLICITADA;

drop index RELATIONSHIP_25_FK;

drop index RELATIONSHIP_23_FK;

drop index RESPONSABLE_RESERVA_PK;

drop table RESPONSABLE_RESERVA;

drop index RELATIONSHIP_32_FK;

drop index RELATIONSHIP_30_FK;

drop index RESPUESTA_PK;

drop table RESPUESTA;

drop index ROL_PK;

drop table ROL;

drop index SOLICITUD_RESERVA_PK;

drop table SOLICITUD_RESERVA;

drop index RELATIONSHIP_17_FK;

drop index TELEFONO_PK;

drop table TELEFONO;

drop index RELATIONSHIP_20_FK;

drop index TELEFONO_USUARIO_PK;

drop table TELEFONO_USUARIO;

drop index RELATIONSHIP_29_FK;

drop index RELATIONSHIP_28_FK;

drop index TIENE_MATERIA_PK;

drop table TIENE_MATERIA;

drop index TOLERANCIA_PK;

drop table TOLERANCIA;

drop index RELATIONSHIP_22_FK;

drop index USUARIO_PK;

drop table USUARIO;

drop index RELATIONSHIP_33_FK;

drop index USUARIO_LOG_PK;

drop table USUARIO_LOG;

drop index BLOQUEO_FECHA_PK;

drop table BLOQUEO_FECHA;

/*==============================================================*/
/* Table: ACTIVIDAD                                             */
/*==============================================================*/
create table ACTIVIDAD (
   ID_CONTENIDO         INT4                 not null,
   TITULO               TEXT                 null,
   PERMITE_RESERVA      NUMERIC(3)           null,
   constraint PK_ACTIVIDAD primary key (ID_CONTENIDO)
);

/*==============================================================*/
/* Index: ACTIVIDAD_PK                                          */
/*==============================================================*/
create unique index ACTIVIDAD_PK on ACTIVIDAD (
ID_CONTENIDO
);

/*==============================================================*/
/* Table: ASUNTO                                                */
/*==============================================================*/
create table ASUNTO (
   ID_ASUNTO            SERIAL               not null,
   ASUNTO               TEXT                 null,
   constraint PK_ASUNTO primary key (ID_ASUNTO)
);

/*==============================================================*/
/* Index: ASUNTO_PK                                             */
/*==============================================================*/
create unique index ASUNTO_PK on ASUNTO (
ID_ASUNTO
);

/*==============================================================*/
/* Table: CONFIGURACION                                         */
/*==============================================================*/
create table CONFIGURACION (
   ID_CONFIGURACION     SERIAL               not null,
   ANIO                 NUMERIC(10)          not null,
   GESTION              NUMERIC(10)          not null,
   DURACION_PERIODO     TEXT                 null,
   HORA_INICIO_JORNADA  TEXT                 null,
   HORA_FIN_JORNADA     TEXT                 null,
   HORA_FIN_SABADO      TEXT                 null,
   constraint PK_CONFIGURACION primary key (ID_CONFIGURACION, ANIO, GESTION)
);

/*==============================================================*/
/* Index: CONFIGURACION_PK                                      */
/*==============================================================*/
create unique index CONFIGURACION_PK on CONFIGURACION (
ID_CONFIGURACION,
ANIO,
GESTION
);

/*==============================================================*/
/* Table: CONTENIDO                                             */
/*==============================================================*/
create table CONTENIDO (
   ID_CONTENIDO         SERIAL               not null,
   ANIO                 NUMERIC(10)          not null,
   GESTION              NUMERIC(10)          not null,
   FECHA_HORA_INICIO    TIMESTAMP            null,
   FECHA_HORA_FIN       TIMESTAMP            null,
   DESCRIPCION          TEXT                 null,
   constraint PK_CONTENIDO primary key (ID_CONTENIDO)
);

/*==============================================================*/
/* Index: CONTENIDO_PK                                          */
/*==============================================================*/
create unique index CONTENIDO_PK on CONTENIDO (
ID_CONTENIDO
);

/*==============================================================*/
/* Index: TIENE_FK                                              */
/*==============================================================*/
create  index TIENE_FK on CONTENIDO (
ANIO,
GESTION
);

/*==============================================================*/
/* Table: CORREO                                                */
/*==============================================================*/
create table CORREO (
   ID_SOLICITUD_RESERVA INT4                 not null,
   CORREO1              TEXT                 not null,
   constraint PK_CORREO primary key (ID_SOLICITUD_RESERVA, CORREO1)
);

/*==============================================================*/
/* Index: CORREO_PK                                             */
/*==============================================================*/
create unique index CORREO_PK on CORREO (
ID_SOLICITUD_RESERVA,
CORREO1
);

/*==============================================================*/
/* Index: RELATIONSHIP_16_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_16_FK on CORREO (
ID_SOLICITUD_RESERVA
);

/*==============================================================*/
/* Table: CORREO_USUARIO                                        */
/*==============================================================*/
create table CORREO_USUARIO (
   NOMBRE_USUARIO       TEXT                 not null,
   CORREO               TEXT                 not null,
   constraint PK_CORREO_USUARIO primary key (NOMBRE_USUARIO, CORREO)
);

/*==============================================================*/
/* Index: CORREO_USUARIO_PK                                     */
/*==============================================================*/
create unique index CORREO_USUARIO_PK on CORREO_USUARIO (
NOMBRE_USUARIO,
CORREO
);

/*==============================================================*/
/* Index: RELATIONSHIP_19_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_19_FK on CORREO_USUARIO (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Table: CRONOGRAMA_ACADEMICO                                  */
/*==============================================================*/
create table CRONOGRAMA_ACADEMICO (
   ANIO                 NUMERIC(10)          not null,
   GESTION              NUMERIC(10)          not null,
   FECHA_HORA_INICIO    TIMESTAMP            null,
   FECHA_HORA_FIN       TIMESTAMP            null,
   FECHA_ACTIVACION     DATE                 null,
   constraint PK_CRONOGRAMA_ACADEMICO primary key (ANIO, GESTION)
);

/*==============================================================*/
/* Index: CRONOGRAMA_ACADEMICO_PK                               */
/*==============================================================*/
create unique index CRONOGRAMA_ACADEMICO_PK on CRONOGRAMA_ACADEMICO (
ANIO,
GESTION
);

/*==============================================================*/
/* Table: FECHAS_NACIONALES                                     */
/*==============================================================*/
create table FECHAS_NACIONALES (
   ID_FECHA_NACIONAL    SERIAL               not null,
   TITULO               TEXT                 null,
   DIA                  NUMERIC(11)          null,
   MES                  NUMERIC(11)          null,
   FERIADO              NUMERIC(1)           null,
   constraint PK_FECHAS_NACIONALES primary key (ID_FECHA_NACIONAL)
);

/*==============================================================*/
/* Index: FECHAS_NACIONALES_PK                                  */
/*==============================================================*/
create unique index FECHAS_NACIONALES_PK on FECHAS_NACIONALES (
ID_FECHA_NACIONAL
);

/*==============================================================*/
/* Table: FERIADO_ESPECIAL                                      */
/*==============================================================*/
create table FERIADO_ESPECIAL (
   ID_CONTENIDO         INT4                 not null,
   TITULO               TEXT                 null,
   constraint PK_FERIADO_ESPECIAL primary key (ID_CONTENIDO)
);

/*==============================================================*/
/* Index: FERIADO_ESPECIAL_PK                                   */
/*==============================================================*/
create unique index FERIADO_ESPECIAL_PK on FERIADO_ESPECIAL (
ID_CONTENIDO
);

/*==============================================================*/
/* Table: MATERIA                                               */
/*==============================================================*/
create table MATERIA (
   CODIGO_MATERIA       SERIAL               not null,
   NOMBRE_MATERIA       TEXT                 null,
   constraint PK_MATERIA primary key (CODIGO_MATERIA)
);

/*==============================================================*/
/* Index: MATERIA_PK                                            */
/*==============================================================*/
create unique index MATERIA_PK on MATERIA (
CODIGO_MATERIA
);

/*==============================================================*/
/* Table: OTRO                                                  */
/*==============================================================*/
create table OTRO (
   ID_CONTENIDO         INT4                 not null,
   TITULO               TEXT                 null,
   CIERRE_UNIVERSIDAD   NUMERIC(1)           null,
   constraint PK_OTRO primary key (ID_CONTENIDO)
);

/*==============================================================*/
/* Index: OTRO_PK                                               */
/*==============================================================*/
create unique index OTRO_PK on OTRO (
ID_CONTENIDO
);

/*==============================================================*/
/* Table: PRIVILEGIO                                            */
/*==============================================================*/
create table PRIVILEGIO (
   NOMBRE_ROL           TEXT                 not null,
   NOMBRE_PRIVILEGIO    TEXT                 not null,
   constraint PK_PRIVILEGIO primary key (NOMBRE_ROL, NOMBRE_PRIVILEGIO)
);

/*==============================================================*/
/* Index: PRIVILEGIO_PK                                         */
/*==============================================================*/
create unique index PRIVILEGIO_PK on PRIVILEGIO (
NOMBRE_ROL,
NOMBRE_PRIVILEGIO
);

/*==============================================================*/
/* Index: RELATIONSHIP_18_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_18_FK on PRIVILEGIO (
NOMBRE_ROL
);

/*==============================================================*/
/* Table: RESERVA                                               */
/*==============================================================*/
create table RESERVA (
   ID_RESERVA           SERIAL               not null,
   FECHA                DATE                 null,
   HORA_INICIO          TEXT                 null,
   HORA_FIN             TEXT                 null,
   EVENTO               TEXT                 null,
   constraint PK_RESERVA primary key (ID_RESERVA)
);

/*==============================================================*/
/* Index: RESERVA_PK                                            */
/*==============================================================*/
create unique index RESERVA_PK on RESERVA (
ID_RESERVA
);

/*==============================================================*/
/* Table: RESERVA_ACADEMICA                                     */
/*==============================================================*/
create table RESERVA_ACADEMICA (
   ID_RESERVA           INT4                 not null,
   ID_ASUNTO            INT4                 null,
   CODIGO_MATERIA       INT4                 null,
   ID_CONTENIDO         INT4                 null,
   constraint PK_RESERVA_ACADEMICA primary key (ID_RESERVA)
);

/*==============================================================*/
/* Index: RESERVA_ACADEMICA_PK                                  */
/*==============================================================*/
create unique index RESERVA_ACADEMICA_PK on RESERVA_ACADEMICA (
ID_RESERVA
);

/*==============================================================*/
/* Index: RELATIONSHIP_9_FK                                     */
/*==============================================================*/
create  index RELATIONSHIP_9_FK on RESERVA_ACADEMICA (
ID_CONTENIDO
);

/*==============================================================*/
/* Index: RELATIONSHIP_10_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_10_FK on RESERVA_ACADEMICA (
ID_ASUNTO
);

/*==============================================================*/
/* Index: RELATIONSHIP_26_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_26_FK on RESERVA_ACADEMICA (
CODIGO_MATERIA
);

/*==============================================================*/
/* Table: RESERVA_LOG                                           */
/*==============================================================*/
create table RESERVA_LOG (
   FECHA                DATE                 null,
   HORA_INICIO          TEXT                 null,
   HORA_FIN             TEXT                 null,
   EVENTO               TEXT                 null,
   FECHA_MODIFICADA     DATE                 null,
   USUARIO_MODIFICO     TEXT                 null,
   ACCION               TEXT                 null,
   ID_RESERVA1          SERIAL               not null,
   constraint PK_RESERVA_LOG primary key (ID_RESERVA1)
);

/*==============================================================*/
/* Index: RESERVA_LOG_PK                                        */
/*==============================================================*/
create unique index RESERVA_LOG_PK on RESERVA_LOG (
ID_RESERVA1
);

/*==============================================================*/
/* Table: RESERVA_SOLICITADA                                    */
/*==============================================================*/
create table RESERVA_SOLICITADA (
   ID_RESERVA           INT4                 not null,
   RESPONSABLE          TEXT                 null,
   DESCRIPCION          TEXT                 null,
   INSTITUCION          TEXT                 null,
   constraint PK_RESERVA_SOLICITADA primary key (ID_RESERVA)
);

/*==============================================================*/
/* Index: RESERVA_SOLICITADA_PK                                 */
/*==============================================================*/
create unique index RESERVA_SOLICITADA_PK on RESERVA_SOLICITADA (
ID_RESERVA
);

/*==============================================================*/
/* Table: RESPONSABLE_RESERVA                                   */
/*==============================================================*/
create table RESPONSABLE_RESERVA (
   ID_RESERVA           INT4                 not null,
   NOMBRE_USUARIO       TEXT                 not null,
   constraint PK_RESPONSABLE_RESERVA primary key (ID_RESERVA, NOMBRE_USUARIO)
);

/*==============================================================*/
/* Index: RESPONSABLE_RESERVA_PK                                */
/*==============================================================*/
create unique index RESPONSABLE_RESERVA_PK on RESPONSABLE_RESERVA (
ID_RESERVA,
NOMBRE_USUARIO
);

/*==============================================================*/
/* Index: RELATIONSHIP_23_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_23_FK on RESPONSABLE_RESERVA (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Index: RELATIONSHIP_25_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_25_FK on RESPONSABLE_RESERVA (
ID_RESERVA
);

/*==============================================================*/
/* Table: RESPUESTA                                             */
/*==============================================================*/
create table RESPUESTA (
   ACEPTADO             NUMERIC(3)           null,
   MENSAJE              TEXT                 null,
   CARGO_RESPONSABLE    TEXT                 null,
   RESPONSABLE          TEXT                 null,
   ID_RESPUESTA         SERIAL               not null,
   ID_SOLICITUD_RESERVA INT4                 null,
   ID_RESERVA           INT4                 null,
   REPRESENTANTE        TEXT                 null,
   CARGO_REPRESENTANTE  TEXT                 null,
   constraint PK_RESPUESTA primary key (ID_RESPUESTA)
);

/*==============================================================*/
/* Index: RESPUESTA_PK                                          */
/*==============================================================*/
create unique index RESPUESTA_PK on RESPUESTA (
ID_RESPUESTA
);

/*==============================================================*/
/* Index: RELATIONSHIP_30_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_30_FK on RESPUESTA (
ID_SOLICITUD_RESERVA
);

/*==============================================================*/
/* Index: RELATIONSHIP_32_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_32_FK on RESPUESTA (
ID_RESERVA
);

/*==============================================================*/
/* Table: ROL                                                   */
/*==============================================================*/
create table ROL (
   NOMBRE_ROL           TEXT                 not null,
   PUEDE_TENER_MATERIAS NUMERIC(3)           null,
   constraint PK_ROL primary key (NOMBRE_ROL)
);

/*==============================================================*/
/* Index: ROL_PK                                                */
/*==============================================================*/
create unique index ROL_PK on ROL (
NOMBRE_ROL
);

/*==============================================================*/
/* Table: SOLICITUD_RESERVA                                     */
/*==============================================================*/
create table SOLICITUD_RESERVA (
   ID_SOLICITUD_RESERVA SERIAL               not null,
   LEIDO                NUMERIC(1)           null,
   FECHA                DATE                 null,
   HORA_INICIO          TEXT                 null,
   HORA_FIN             TEXT                 null,
   RESPONSABLE          TEXT                 null,
   INSTITUCION          TEXT                 null,
   EVENTO               TEXT                 null,
   DESCRIPCION          TEXT                 null,
   constraint PK_SOLICITUD_RESERVA primary key (ID_SOLICITUD_RESERVA)
);

/*==============================================================*/
/* Index: SOLICITUD_RESERVA_PK                                  */
/*==============================================================*/
create unique index SOLICITUD_RESERVA_PK on SOLICITUD_RESERVA (
ID_SOLICITUD_RESERVA
);

/*==============================================================*/
/* Table: TELEFONO                                              */
/*==============================================================*/
create table TELEFONO (
   ID_SOLICITUD_RESERVA INT4                 not null,
   TELEFONO1            NUMERIC(10)          not null,
   constraint PK_TELEFONO primary key (ID_SOLICITUD_RESERVA, TELEFONO1)
);

/*==============================================================*/
/* Index: TELEFONO_PK                                           */
/*==============================================================*/
create unique index TELEFONO_PK on TELEFONO (
ID_SOLICITUD_RESERVA,
TELEFONO1
);

/*==============================================================*/
/* Index: RELATIONSHIP_17_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_17_FK on TELEFONO (
ID_SOLICITUD_RESERVA
);

/*==============================================================*/
/* Table: TELEFONO_USUARIO                                      */
/*==============================================================*/
create table TELEFONO_USUARIO (
   NOMBRE_USUARIO       TEXT                 not null,
   TELEFONO             NUMERIC(10)          not null,
   constraint PK_TELEFONO_USUARIO primary key (NOMBRE_USUARIO, TELEFONO)
);

/*==============================================================*/
/* Index: TELEFONO_USUARIO_PK                                   */
/*==============================================================*/
create unique index TELEFONO_USUARIO_PK on TELEFONO_USUARIO (
NOMBRE_USUARIO,
TELEFONO
);

/*==============================================================*/
/* Index: RELATIONSHIP_20_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_20_FK on TELEFONO_USUARIO (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Table: TIENE_MATERIA                                         */
/*==============================================================*/
create table TIENE_MATERIA (
   NOMBRE_USUARIO       TEXT                 not null,
   CODIGO_MATERIA       INT4                 not null,
   constraint PK_TIENE_MATERIA primary key (NOMBRE_USUARIO, CODIGO_MATERIA)
);

/*==============================================================*/
/* Index: TIENE_MATERIA_PK                                      */
/*==============================================================*/
create unique index TIENE_MATERIA_PK on TIENE_MATERIA (
NOMBRE_USUARIO,
CODIGO_MATERIA
);

/*==============================================================*/
/* Index: RELATIONSHIP_28_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_28_FK on TIENE_MATERIA (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Index: RELATIONSHIP_29_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_29_FK on TIENE_MATERIA (
CODIGO_MATERIA
);

/*==============================================================*/
/* Table: TOLERANCIA                                            */
/*==============================================================*/
create table TOLERANCIA (
   ID_CONTENIDO         INT4                 not null,
   constraint PK_TOLERANCIA primary key (ID_CONTENIDO)
);

/*==============================================================*/
/* Index: TOLERANCIA_PK                                         */
/*==============================================================*/
create unique index TOLERANCIA_PK on TOLERANCIA (
ID_CONTENIDO
);

/*==============================================================*/
/* Table: USUARIO                                               */
/*==============================================================*/
create table USUARIO (
   NOMBRE_ROL           TEXT                 null,
   NOMBRE_USUARIO       TEXT                 not null,
   CONTRASENIA          TEXT                 null,
   NOMBRES              TEXT                 null,
   APELLIDOS            TEXT                 null,
   constraint PK_USUARIO primary key (NOMBRE_USUARIO)
);

/*==============================================================*/
/* Index: USUARIO_PK                                            */
/*==============================================================*/
create unique index USUARIO_PK on USUARIO (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Index: RELATIONSHIP_22_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_22_FK on USUARIO (
NOMBRE_ROL
);

/*==============================================================*/
/* Table: USUARIO_LOG                                           */
/*==============================================================*/
create table USUARIO_LOG (
   NOMBRE_USUARIO       TEXT                 not null,
   ID_USUARIO_LOG       SERIAL               not null,
   NOMBRES              TEXT                 null,
   APELLIDOS            TEXT                 null,
   FECHA                DATE                 null,
   USUARIO              CHAR(10)             null,
   ACCION               TEXT                 null,
   constraint PK_USUARIO_LOG primary key (NOMBRE_USUARIO, ID_USUARIO_LOG)
);

/*==============================================================*/
/* Index: USUARIO_LOG_PK                                        */
/*==============================================================*/
create unique index USUARIO_LOG_PK on USUARIO_LOG (
NOMBRE_USUARIO,
ID_USUARIO_LOG
);

/*==============================================================*/
/* Index: RELATIONSHIP_33_FK                                    */
/*==============================================================*/
create  index RELATIONSHIP_33_FK on USUARIO_LOG (
NOMBRE_USUARIO
);

/*==============================================================*/
/* Table: BLOQUEO_FECHA                                   */
/*==============================================================*/
create table BLOQUEO_FECHA (
   ID_BLOQUEO           SERIAL               not null,
   BLOQUEADO            NUMERIC(1)           null,
   FECHA                DATE                 null,
   FECHA_HORA_BLOQUEO   TIMESTAMP            null,
   constraint PK_BLOQUEO_FECHA primary key (ID_BLOQUEO)
);

/*==============================================================*/
/* Index: SOLICITUD_RESERVA_PK                                  */
/*==============================================================*/
create unique index BLOQUEO_FECHA_PK on BLOQUEO_FECHA (
ID_BLOQUEO
);

alter table ACTIVIDAD
   add constraint FK_ACTIVIDA_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete restrict on update restrict;

alter table CONFIGURACION
   add constraint FK_CONFIGUR_TIENE1_CRONOGRA foreign key (ANIO, GESTION)
      references CRONOGRAMA_ACADEMICO (ANIO, GESTION)
      on delete restrict on update restrict;

alter table CONTENIDO
   add constraint FK_CONTENID_TIENE_CRONOGRA foreign key (ANIO, GESTION)
      references CRONOGRAMA_ACADEMICO (ANIO, GESTION)
      on delete restrict on update restrict;

alter table CORREO
   add constraint FK_CORREO_RELATIONS_SOLICITU foreign key (ID_SOLICITUD_RESERVA)
      references SOLICITUD_RESERVA (ID_SOLICITUD_RESERVA)
      on delete restrict on update restrict;

alter table CORREO_USUARIO
   add constraint FK_CORREO_U_RELATIONS_USUARIO foreign key (NOMBRE_USUARIO)
      references USUARIO (NOMBRE_USUARIO)
      on delete restrict on update restrict;

alter table FERIADO_ESPECIAL
   add constraint FK_FERIADO__RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete restrict on update restrict;

alter table OTRO
   add constraint FK_OTRO_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete restrict on update restrict;

alter table PRIVILEGIO
   add constraint FK_PRIVILEG_RELATIONS_ROL foreign key (NOMBRE_ROL)
      references ROL (NOMBRE_ROL)
      on delete restrict on update restrict;

alter table RESERVA_ACADEMICA
   add constraint FK_RESERVA__RELATIONS_ASUNTO foreign key (ID_ASUNTO)
      references ASUNTO (ID_ASUNTO)
      on delete restrict on update restrict;

alter table RESERVA_ACADEMICA
   add constraint FK_RESERVA__RELATIONS_RESERVA foreign key (ID_RESERVA)
      references RESERVA (ID_RESERVA)
      on delete restrict on update restrict;

alter table RESERVA_ACADEMICA
   add constraint FK_RESERVA__RELATIONS_MATERIA foreign key (CODIGO_MATERIA)
      references MATERIA (CODIGO_MATERIA)
      on delete restrict on update restrict;

alter table RESERVA_ACADEMICA
   add constraint FK_RESERVA__RELATIONS_ACTIVIDA foreign key (ID_CONTENIDO)
      references ACTIVIDAD (ID_CONTENIDO)
      on delete restrict on update restrict;

alter table RESERVA_SOLICITADA
   add constraint FK_RESERVA__RELATIONS_RESERVA foreign key (ID_RESERVA)
      references RESERVA (ID_RESERVA)
      on delete restrict on update restrict;

alter table RESPONSABLE_RESERVA
   add constraint FK_RESPONSA_RELATIONS_USUARIO foreign key (NOMBRE_USUARIO)
      references USUARIO (NOMBRE_USUARIO)
      on delete restrict on update restrict;

alter table RESPONSABLE_RESERVA
   add constraint FK_RESPONSA_RELATIONS_RESERVA_ foreign key (ID_RESERVA)
      references RESERVA_ACADEMICA (ID_RESERVA)
      on delete restrict on update restrict;

alter table RESPUESTA
   add constraint FK_RESPUEST_RELATIONS_SOLICITU foreign key (ID_SOLICITUD_RESERVA)
      references SOLICITUD_RESERVA (ID_SOLICITUD_RESERVA)
      on delete restrict on update restrict;

alter table RESPUESTA
   add constraint FK_RESPUEST_RELATIONS_RESERVA_ foreign key (ID_RESERVA)
      references RESERVA_SOLICITADA (ID_RESERVA)
      on delete restrict on update restrict;

alter table TELEFONO
   add constraint FK_TELEFONO_RELATIONS_SOLICITU foreign key (ID_SOLICITUD_RESERVA)
      references SOLICITUD_RESERVA (ID_SOLICITUD_RESERVA)
      on delete restrict on update restrict;

alter table TELEFONO_USUARIO
   add constraint FK_TELEFONO_RELATIONS_USUARIO foreign key (NOMBRE_USUARIO)
      references USUARIO ( NOMBRE_USUARIO)
      on delete restrict on update restrict;

alter table TIENE_MATERIA
   add constraint FK_TIENE_MA_RELATIONS_USUARIO foreign key (NOMBRE_USUARIO)
      references USUARIO (NOMBRE_USUARIO)
      on delete restrict on update restrict;

alter table TIENE_MATERIA
   add constraint FK_TIENE_MA_RELATIONS_MATERIA foreign key (CODIGO_MATERIA)
      references MATERIA (CODIGO_MATERIA)
      on delete restrict on update restrict;

alter table TOLERANCIA
   add constraint FK_TOLERANC_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete restrict on update restrict;

alter table USUARIO
   add constraint FK_USUARIO_RELATIONS_ROL foreign key (NOMBRE_ROL)
      references ROL (NOMBRE_ROL)
      on delete restrict on update restrict;

alter table USUARIO_LOG
   add constraint FK_USUARIO__RELATIONS_USUARIO foreign key (NOMBRE_USUARIO)
      references USUARIO (NOMBRE_USUARIO)
      on delete restrict on update restrict;

ALTER TABLE "reserva_solicitada" 
   ADD COLUMN id_respuesta integer;

ALTER TABLE "reserva_solicitada" 
   ADD CONSTRAINT fk_ressol_respuesta
   FOREIGN KEY (id_respuesta) 
   REFERENCES "respuesta"(id_respuesta);


alter table reserva_academica 
drop constraint fk_reserva__relations_reserva,
add constraint fk_reserva__relations_reserva foreign key(id_reserva) references public.reserva(id_reserva) on delete cascade;


alter table reserva_solicitada
drop constraint fk_reserva__relations_reserva,
add constraint fk_reserva__relations_reserva foreign key(id_reserva) references public.reserva(id_reserva) on delete cascade;


alter table responsable_reserva
drop constraint FK_RESPONSA_RELATIONS_RESERVA_,
add constraint FK_RESPONSA_RELATIONS_RESERVA_ foreign key(id_reserva) references public.reserva(id_reserva) on delete cascade;

alter table ACTIVIDAD
   drop constraint FK_ACTIVIDA_RELATIONS_CONTENID,
   add constraint FK_ACTIVIDA_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete cascade on update cascade;

alter table FERIADO_ESPECIAL
   drop constraint FK_FERIADO__RELATIONS_CONTENID,
   add constraint FK_FERIADO__RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete cascade on update cascade;

alter table OTRO
   drop constraint FK_OTRO_RELATIONS_CONTENID,
   add constraint FK_OTRO_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete cascade on update cascade;


alter table TOLERANCIA
   drop constraint FK_TOLERANC_RELATIONS_CONTENID,
   add constraint FK_TOLERANC_RELATIONS_CONTENID foreign key (ID_CONTENIDO)
      references CONTENIDO (ID_CONTENIDO)
      on delete cascade on update cascade;


alter table RESERVA_ACADEMICA
   drop constraint FK_RESERVA__RELATIONS_ACTIVIDA,
   add constraint FK_RESERVA__RELATIONS_ACTIVIDA foreign key (ID_CONTENIDO)
      references ACTIVIDAD (ID_CONTENIDO)
      on delete restrict on update cascade;

alter table reserva add constraint uniqueconstraint unique (fecha, hora_inicio, hora_fin);

alter table BLOQUEO_FECHA add constraint fecha_unique_constraint unique (fecha);


DROP FUNCTION bloquear(date);

CREATE OR REPLACE FUNCTION bloquear(fecha_bloq date)
  RETURNS bigint AS
$BODY$
DECLARE
   fecha_bloqueo TIMESTAMP;
BEGIN
IF (SELECT COUNT(*) FROM BLOQUEO_FECHA WHERE fecha=$1)>0 THEN
   IF (SELECT BLOQUEADO FROM BLOQUEO_FECHA WHERE fecha=$1)=1.0 THEN
      fecha_bloqueo := (SELECT FECHA_HORA_BLOQUEO FROM BLOQUEO_FECHA WHERE fecha=$1);
      IF (SELECT (DATE_PART('day', (select now())::timestamp - fecha_bloqueo::timestamp) * 24 + 
               DATE_PART('hour', (select now())::timestamp - fecha_bloqueo::timestamp)) * 60 +
               DATE_PART('minute', (select now())::timestamp - fecha_bloqueo::timestamp))>10.0 THEN
         UPDATE BLOQUEO_FECHA SET BLOQUEADO=1, FECHA_HORA_BLOQUEO=(select now()) WHERE FECHA=$1;
         RETURN 1;
      ELSE
         RETURN 0;
      END IF;
   ELSE
      UPDATE BLOQUEO_FECHA SET BLOQUEADO=1, FECHA_HORA_BLOQUEO=(select now()) WHERE FECHA=$1;
      RETURN 1; 
   END IF;
ELSE
   INSERT INTO BLOQUEO_FECHA (fecha, BLOQUEADO, FECHA_HORA_BLOQUEO) VALUES ($1, 1, (select now()));
   RETURN 1;
END IF;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION bloquear(date)
  OWNER TO postgres;

DROP FUNCTION desbloquear(date);

CREATE OR REPLACE FUNCTION desbloquear(fecha_desbloq date)
  RETURNS bigint AS
$BODY$
BEGIN
IF (SELECT COUNT(*) FROM BLOQUEO_FECHA WHERE fecha=$1)>0 THEN
   IF (SELECT BLOQUEADO FROM BLOQUEO_FECHA WHERE fecha=$1)=1.0 THEN
      UPDATE BLOQUEO_FECHA SET BLOQUEADO=0, FECHA_HORA_BLOQUEO=null WHERE FECHA=$1;
      RETURN 1;
   ELSE
      RETURN 1; 
   END IF;
ELSE
   RETURN 0;
END IF;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION desbloquear(date)
  OWNER TO postgres;