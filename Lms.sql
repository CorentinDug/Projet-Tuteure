#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#pour creer la meme base de données que moi :
create database if not exists projet_tut;

drop table if exists sert;
drop table if exists commentaires;
drop table if exists RESERVATION;
drop table if exists MENU;
drop table if exists TYPE;
drop table if exists SUPPLEMENT;
drop table if exists BOISSON;
drop table if exists APERITIF;
drop table if exists DESSERT;
drop table if exists FROMAGE;
drop table if exists Etudiant;
drop table if exists PLAT;
drop table if exists ENTREE;
drop table if exists CLIENT;

#------------------------------------------------------------
# Table: CLIENT
#------------------------------------------------------------

CREATE TABLE CLIENT(
        id_client     int (11) Auto_increment  NOT NULL ,
        nom_client    Text ,
        prenom_client Text ,
        mail_client   Text ,
        tel_client    Text ,
        PRIMARY KEY (id_client )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: RESERVATION
#------------------------------------------------------------

CREATE TABLE RESERVATION(
        id_reservation int (11) Auto_increment  NOT NULL ,
        nbplaces       Int ,
        id_client      Int ,
        id_menu        Int ,
        PRIMARY KEY (id_reservation )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: MENU
#------------------------------------------------------------

CREATE TABLE MENU(
        id_menu       int (11) Auto_increment,
        libelle_menu  Text ,
        nbDispo       Int ,
        prix          Int ,
        date_menu     DATE ,
        id_type       Int ,
        id_aperitif   Int ,
        id_entree     Int ,
        id_plat       Int ,
        id_fromage    Int ,
        id_dessert    Int ,
        id_boisson    Int ,
        id_supplement Int ,
        PRIMARY KEY (id_menu )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: TYPE
#------------------------------------------------------------

CREATE TABLE TYPE(
        id_type      int (11) Auto_increment  NOT NULL ,
        libelle_type Text ,
        PRIMARY KEY (id_type )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ENTREE
#------------------------------------------------------------

CREATE TABLE ENTREE(
        id_entree      int (11) Auto_increment  NOT NULL ,
        libelle_entree Text ,
        PRIMARY KEY (id_entree )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: PLAT
#------------------------------------------------------------

CREATE TABLE PLAT(
        id_plat      int (11) Auto_increment  NOT NULL ,
        libelle_plat Text ,
        PRIMARY KEY (id_plat )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Etudiant
#------------------------------------------------------------

CREATE TABLE Etudiant(
        id_etu       int (11) Auto_increment  NOT NULL ,
        nom_etu      Text ,
        prenom_etu   Text ,
        PRIMARY KEY (id_etu )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: FROMAGE
#------------------------------------------------------------

CREATE TABLE FROMAGE(
        id_fromage      int (11) Auto_increment  NOT NULL ,
        libelle_fromage Text ,
        PRIMARY KEY (id_fromage )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: DESSERT
#------------------------------------------------------------

CREATE TABLE DESSERT(
        id_dessert      int (11) Auto_increment  NOT NULL ,
        libelle_dessert Text ,
        PRIMARY KEY (id_dessert )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: APERITIF
#------------------------------------------------------------

CREATE TABLE APERITIF(
        id_aperitif      int (11) Auto_increment  NOT NULL ,
        libelle_aperitif Text ,
        PRIMARY KEY (id_aperitif )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: BOISSON
#------------------------------------------------------------

CREATE TABLE BOISSON(
        id_boisson   int (11) Auto_increment  NOT NULL ,
        type_boisson Text ,
        PRIMARY KEY (id_boisson )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: SUPPLEMENT
#------------------------------------------------------------

CREATE TABLE SUPPLEMENT(
        id_supplement   int (11) Auto_increment  NOT NULL ,
        type_supplement Text ,
        PRIMARY KEY (id_supplement )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: sert
#------------------------------------------------------------

CREATE TABLE sert(
        id_reservation Int NOT NULL ,
        id_etu         Int NOT NULL ,
        PRIMARY KEY (id_reservation ,id_etu )
)ENGINE=InnoDB;

DROP TABLE IF EXISTS users;


create table commentaires
(
        id_client int not null,
        id_reservation int not null,
        Commentaire text null,
        primary key (id_reservation, id_client)
)ENGINE =InnoDB;


# <http://silex.sensiolabs.org/doc/2.0/providers/security.html#defining-a-custom-user-provider>
# Contenu de la table `utilisateur`

CREATE TABLE users (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        username VARCHAR(100) NOT NULL DEFAULT '',
        password VARCHAR(255) NOT NULL DEFAULT '',
        motdepasse VARCHAR(255) NOT NULL DEFAULT '',
        roles VARCHAR(255) NOT NULL DEFAULT '',
        email  VARCHAR(255) NOT NULL DEFAULT '',
        isEnabled TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# mot de passe crypté avec security.encoder.bcrypt

INSERT INTO users (id,username,password,motdepasse,email,roles) VALUES
        (1, 'admin', 'd05cc09587a5589671f59966bea4fb12', 'admin', 'admin@gmail.com','ROLE_ADMIN'),
        (2, 'client', '2f9dab7127378d55a4121d855266074c', 'client', 'client@gmail.com','ROLE_CLIENT'),
        (3, 'client2', '2b49abae6e13396373d67063c6473efb','client2', 'client2@gmail.com','ROLE_CLIENT');

ALTER TABLE RESERVATION ADD CONSTRAINT FK_RESERVATION_id_client FOREIGN KEY (id_client) REFERENCES CLIENT(id_client) ON DELETE CASCADE;
ALTER TABLE RESERVATION ADD CONSTRAINT FK_RESERVATION_ FOREIGN KEY (id_menu) REFERENCES MENU(id_menu) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_type FOREIGN KEY (id_type) REFERENCES TYPE(id_type) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_aperitif FOREIGN KEY (id_aperitif) REFERENCES APERITIF(id_aperitif) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_entree FOREIGN KEY (id_entree) REFERENCES ENTREE(id_entree) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_plat FOREIGN KEY (id_plat) REFERENCES PLAT(id_plat) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_fromage FOREIGN KEY (id_fromage) REFERENCES FROMAGE(id_fromage) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_dessert FOREIGN KEY (id_dessert) REFERENCES DESSERT(id_dessert) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_boisson FOREIGN KEY (id_boisson) REFERENCES BOISSON(id_boisson) ON DELETE CASCADE;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_supplement FOREIGN KEY (id_supplement) REFERENCES SUPPLEMENT(id_supplement) ON DELETE CASCADE;
ALTER TABLE sert ADD CONSTRAINT FK_sert_id_reservation FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id_reservation) ON DELETE CASCADE;
ALTER TABLE sert ADD CONSTRAINT FK_sert_id_etu FOREIGN KEY (id_etu) REFERENCES Etudiant(id_etu) ON DELETE CASCADE;
ALTER TABLE commentaires ADD CONSTRAINT FK_commentaire_id_reservation FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id_reservation) ON DELETE CASCADE;
ALTER TABLE commentaires ADD CONSTRAINT FK_commentaire_id_client FOREIGN KEY (id_client) REFERENCES users(id) ON DELETE CASCADE;

/*

                INSERTION CLIENT

*/
insert into CLIENT values (null,"Brussiau","Antoine","truc@gmail.com","0605040301");
insert into CLIENT values (null,"Escobar","Benjamin","truc2@gmail.com","0605040302");
insert into CLIENT values (null,"Schnoebelen","Lucas","truc3@gmail.com","0605040303");
insert into CLIENT values (null,"Robert","Julien","truc4@gmail.com","0605040304");
insert into CLIENT values (null,"Garcenot","Thomas","truc5@gmail.com","0605040305");

/*

        INSERTION FROMAGE

*/

insert into FROMAGE values(null,"fromage1");
insert into FROMAGE values(null,"fromage2");
insert into FROMAGE values(null,"fromage3");
insert into FROMAGE values(null,"fromage4");


/*

        INSERTION APERITIF

*/

insert into APERITIF values(null,"curly");
insert into APERITIF values(null,"sangria espagnol");
insert into APERITIF values(null,"cake au reblochon");
insert into APERITIF values(null,"creme au thon");
insert into APERITIF values(null,"cake salé");
insert into APERITIF values(null,"assortiment de toast");
insert into APERITIF values(null,"Tarte soleil au pesto");
insert into APERITIF values(null,"brick au chevre frais");
insert into APERITIF values(null,"roulé feuilleté au jambon");

/*

        INSERTIOn ENTREE

*/

insert into ENTREE values (null,"Salade");
insert into ENTREE values (null,"Salade2");
insert into ENTREE values (null,"Salade3");
insert into ENTREE values (null,"Salade4");


/*

        INSERTION PLAT

*/

insert into PLAT values(null,"plat1");
insert into PLAT values(null,"plat2");
insert into PLAT values(null,"plat3");
insert into PLAT values(null,"plat4");

/*

        INSERTION DESSERT

*/

insert into DESSERT values(null,"dessert1");
insert into DESSERT values(null,"dessert2");
insert into DESSERT values(null,"dessert3");
insert into DESSERT values(null,"dessert4");


/*

        INSERTION BOISSON

*/

insert into BOISSON values(null,"sans boisson");
insert into BOISSON values(null,"boisson1");
insert into BOISSON values(null,"boisson2");
insert into BOISSON values(null,"boisson3");
insert into BOISSON values(null,"boisson4");




/*

        INSERTION SUPPLEMENT

*/
insert into SUPPLEMENT values(null,"sans supplement");
insert into SUPPLEMENT values(null,"supplement1");
insert into SUPPLEMENT values(null,"supplement2");
insert into SUPPLEMENT values(null,"supplement3");
insert into SUPPLEMENT values(null,"supplement4");
/*

        INSERTION TYPE

*/

insert into TYPE values(1,"Dejeuner");
insert into TYPE values(2,"Diner");

/*

        INSERTION MENU

*/
INSERT INTO MENU values (null,'Menu1',6,5,'2017-10-01',1,1,1,1,1,1,1,1);
INSERT INTO MENU values (null,'Menu2',5,4,'2017-10-02',2,2,2,2,2,2,2,2);
INSERT INTO MENU values (null,'Menu3',4,3,'2017-10-03',1,3,3,3,3,3,3,3);
INSERT INTO MENU values (null,'Menu4',3,2,'2017-10-04',2,4,4,4,4,4,4,4);


INSERT INTO projet_tut.users (username, password, motdepasse, roles, email, isEnabled) VALUES ('admin', 'd05cc09587a5589671f59966bea4fb12', 'admin', 'ROLE_ADMIN', 'admin@gmail.com', 1);
INSERT INTO projet_tut.users (username, password, motdepasse, roles, email, isEnabled) VALUES ('client', '2f9dab7127378d55a4121d855266074c', 'client', 'ROLE_CLIENT', 'client@gmail.com', 1);
INSERT INTO projet_tut.users (username, password, motdepasse, roles, email, isEnabled) VALUES ('client2', '2b49abae6e13396373d67063c6473efb', 'client2', 'ROLE_CLIENT', 'client2@gmail.com', 1);

/*

        INSERTION RESERVATION

*/

insert into  RESERVATION values (null,2,1,1);
insert into  RESERVATION values (null,4,2,2);



/*

        INSERTION ETUDIANT


*/

insert into ETUDIANT values(null,"TRUC","machin");
insert into ETUDIANT values(null,"TRUC2","machin2");
insert into ETUDIANT values(null,"TRUC3","machin3");





/*


        INSERTION sert

*/

insert into sert values(1,1);
insert into sert values(2,2);


