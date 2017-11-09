#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


drop table if exists sert;
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
        id_menu       int (11) Auto_increment  NOT NULL ,
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
        fonction_etu Text ,
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
        pres_boisson Bool ,
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

ALTER TABLE RESERVATION ADD CONSTRAINT FK_RESERVATION_id_client FOREIGN KEY (id_client) REFERENCES CLIENT(id_client);
ALTER TABLE RESERVATION ADD CONSTRAINT FK_RESERVATION_ FOREIGN KEY (id_menu) REFERENCES MENU(id_menu);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_type FOREIGN KEY (id_type) REFERENCES TYPE(id_type) ;
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_aperitif FOREIGN KEY (id_aperitif) REFERENCES APERITIF(id_aperitif);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_entree FOREIGN KEY (id_entree) REFERENCES ENTREE(id_entree);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_plat FOREIGN KEY (id_plat) REFERENCES PLAT(id_plat);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_fromage FOREIGN KEY (id_fromage) REFERENCES FROMAGE(id_fromage);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_dessert FOREIGN KEY (id_dessert) REFERENCES DESSERT(id_dessert);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_boisson FOREIGN KEY (id_boisson) REFERENCES BOISSON(id_boisson);
ALTER TABLE MENU ADD CONSTRAINT FK_MENU_id_supplement FOREIGN KEY (id_supplement) REFERENCES SUPPLEMENT(id_supplement);
ALTER TABLE sert ADD CONSTRAINT FK_sert_id_reservation FOREIGN KEY (id_reservation) REFERENCES RESERVATION(id_reservation);
ALTER TABLE sert ADD CONSTRAINT FK_sert_id_etu FOREIGN KEY (id_etu) REFERENCES Etudiant(id_etu);


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

insert into APERITIF values(null,"curly1");
insert into APERITIF values(null,"curly2");
insert into APERITIF values(null,"curly3");
insert into APERITIF values(null,"curly4");

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

insert into DESSERT values(null,"boisson1");
insert into DESSERT values(null,"boisson2");
insert into DESSERT values(null,"boisson3");
insert into DESSERT values(null,"boisson4");



/*

        INSERTION SUPPLEMENT

*/

insert into DESSERT values(null,"supplement1");
insert into DESSERT values(null,"supplement2");
insert into DESSERT values(null,"supplement3");
insert into DESSERT values(null,"supplement4");
/*

        INSERTION MENU

*/
INSERT INTO MENU values (null,'Menu1',6,5,"2017-10-01",1,1,1,1,1,1,1,1);
INSERT INTO MENU values (null,'Menu2',5,4,"2017-10-02",2,2,2,2,2,2,2,2);
INSERT INTO MENU values (null,'Menu3',4,3,"2017-10-03",1,3,3,3,3,3,3,3);
INSERT INTO MENU values (null,'Menu4',3,2,"2017-10-04",2,4,4,4,4,4,4,4);

/*

        INSERTION RESERVATION

*/

insert into  RESERVATION values (null,2,1,1);
insert into  RESERVATION values (null,4,2,2);


/*

        INSERTION TYPE

*/

insert into TYPE values(1,"Dejeuner");
insert into TYPE values(2,"Diner");

/*

        INSERTION ETUDIANT


*/

insert into ETUDIANT values(null,"TRUC","machin","serveur");
insert into ETUDIANT values(null,"TRUC2","machin2","cuisinier");
insert into ETUDIANT values(null,"TRUC3","machin3","serveur");





/*


        INSERTION sert

*/

insert into sert values(1,1);
insert into sert values(2,2);



