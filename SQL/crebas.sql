/*==============================================================*/
/* DBMS name:      Sybase SQL Anywhere 11                       */
/* Created on:     24/07/2025 20:48:46                          */
/*==============================================================*/



/*==============================================================*/
/* Table: CATEGORIE                                             */
/*==============================================================*/
create table CATEGORIE 
(
   ID_CATEGORIE         integer                        not null,
   ID_TYPE              integer                        not null,
   NOM_CATEGORIE        varchar(255)                   null,
   constraint PK_CATEGORIE primary key (ID_CATEGORIE)
);

/*==============================================================*/
/* Table: OPERATION                                             */
/*==============================================================*/
create table OPERATION 
(
   ID_OPERATIONS_       integer                        not null,
   ID_UTILISATEUR       integer                        not null,
   ID_CATEGORIE         integer                        not null,
   MONTANT              integer                        null,
   DATE_OPERATION       date                           null,
   DESCRIPTION          varchar(255)                   null,
   constraint PK_OPERATION primary key (ID_OPERATIONS_)
);

/*==============================================================*/
/* Table: TYPE                                                  */
/*==============================================================*/
create table TYPE 
(
   ID_TYPE              integer                        not null,
   NOM_TYPE             varchar(255)                   null,
   constraint PK_TYPE primary key (ID_TYPE)
);

/*==============================================================*/
/* Table: UTILISATEUR                                           */
/*==============================================================*/
create table UTILISATEUR 
(
   ID_UTILISATEUR       integer                        not null,
   NOM_UTILISATEUR      varchar(255)                   null,
   PRENOM               varchar(255)                   null,
   EMAIL                varchar(255)                   null,
   MOT_DE_PASSE         varchar(255)                   null,
   constraint PK_UTILISATEUR primary key (ID_UTILISATEUR)
);

alter table CATEGORIE
   add constraint FK_CATEGORI_PEUT_ETRE_TYPE foreign key (ID_TYPE)
      references TYPE (ID_TYPE)
      on update restrict
      on delete restrict;

alter table OPERATION
   add constraint FK_OPERATIO_APPARTENI_CATEGORI foreign key (ID_CATEGORIE)
      references CATEGORIE (ID_CATEGORIE)
      on update restrict
      on delete restrict;

alter table OPERATION
   add constraint FK_OPERATIO_EFFECTUER_UTILISAT foreign key (ID_UTILISATEUR)
      references UTILISATEUR (ID_UTILISATEUR)
      on update restrict
      on delete restrict;

