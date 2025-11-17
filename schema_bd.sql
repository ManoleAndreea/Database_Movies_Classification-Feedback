-- test
CREATE DATABASE IF NOT EXISTS feedback_clasificare_filme;
USE feedback_clasificare_filme;

CREATE TABLE detalii_persoana 
(
    id_date int auto_increment primary key,
    nume_persoana varchar(50) not null,
    varsta int, 
    bibliografie text
);

CREATE TABLE gen
(
    id_gen int auto_increment primary key,
    nume_gen varchar(50) not null,
    detalii_gen varchar(255)
);

CREATE TABLE regizor
(
    id_regizor int auto_increment primary key,
    nume_regizor varchar(100) not null,
    id_date int, 
    foreign key (id_date) references detalii_persoana(id_date) on  delete set null
);

CREATE TABLE actor
(
    id_actor int auto_increment primary key,
    nume_actor varchar(100) not null,
    id_date int, 
    foreign key (id_date) references detalii_persoana(id_date) on  delete set null
);

CREATE TABLE film 
(
    id_film int auto_increment primary key,
    nume_film varchar(150) not null,
    an_aparitie int,
    durata int,
    id_regizor int,
    foreign key (id_regizor) references regizor(id_regizor) on delete set null
);

CREATE TABLE film_gen
(
    id_film int,
    id_gen int,
    primary key (id_film, id_gen),
    foreign key (id_film) references film(id_film) on delete cascade,
    foreign key (id_gen) references gen(id_gen) on delete cascade
);

CREATE TABLE distributie
(
    id_film int, 
    id_actor int,
    primary key (id_film, id_actor),
    foreign key (id_film) references film(id_film) on delete cascade,
    foreign key (id_actor) references actor(id_actor) on delete cascade

);

CREATE TABLE feedback
(
    id_feedback int auto_increment primary key,
    id_film int not null,
    continut text,
    nr_stele int check (nr_stele>=1 and nr_stele<=10),
    nume_autor varchar(100),
    foreign key (id_film) references film(id_film) on delete cascade
);