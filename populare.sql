use feedback_clasificare_filme;

insert into detalii_persoana(nume_persoana, varsta, bibliografie) values 
('Ridley Scott', 86, 'Regizor britanic cunoscut pentru filme SF si istorice.'),
('Denis Villeneuve', 56, 'Regizor canadian apreciat pentru stilul vizual unic.'),
('Luca Guadagnino', 52, 'Regizor italian cunoscut pentru estetica senzuala.'),
('Jonathan Demme', 73, 'Regizor american, castigator Oscar.'),
('Stanley Kubrick', 70, 'Unul dintre cei mai influenti regizori din istorie.'),
('Noomi Rapace', 44, 'Actrita suedeza.'),
('Michael Fassbender', 46, 'Actor germano-irlandez.'),
('Amy Adams', 49, 'Actrita americana cunoscuta pentru roluri dramatice.'),
('Sigourney Weaver', 74, 'Pioniera a eroinelor de actiune in SF.'),
('Timothee Chalamet', 28, 'Actor tanar cu numeroase nominalizari.'),
('Armie Hammer', 37, 'Actor american.'),
('Jodie Foster', 61, 'Actrita si regizoare de succes.'),
('Anthony Hopkins', 86, 'Actor galez legendar.'),
('Keir Dullea', 87, 'Actor american cunoscut pentru 2001: A Space Odyssey.');     

insert into gen(nume_gen, detalii_gen) values
('SF', 'Filme Science Fiction, tehnologie, spatiu.'),
('Horror', 'Filme de groaza, suspans.'),
('Drama', 'Filme axate pe dezvoltarea personajelor.'),
('Romance', 'Filme de dragoste.'),
('Thriller', 'Filme cu suspans psihologic.'),
('Mister', 'Filme cu detectivi sau enigme.');

insert into regizor(nume_regizor, id_date) values
('Ridley Scott', 1),
('Denis Villeneuve', 2),
('Luca Guadagnino', 3),
('Jonathan Demme', 4),
('Stanley Kubrick', 5),

insert into actor(nume_actor, id_date) values
('Noomi Rapace', 6)
('Michael Fassbender', 7),
('Amy Adams', 8),
('Sigourney Weaver', 9),
('Timothee Chalamet', 10),
('Armie Hammer', 11),
('Jodie Foster', 12),
('Anthony Hopkins', 13),
('Keir Dullea', 14);

insert into film(nume_film, an_aparitie, durata, id_regizor) values
('Prometheus', 2012, 124, 1),
('Arrival', 2016, 116, 2),
('Alien', 1979, 117, 1),
('Call Me by Your Name', 2017, 132, 3),
('The Silence of the Lambs', 1991, 118, 4),
('2001: A Space Odyssey', 1968, 149, 5);

insert into film_gen(id_film, id_gen) values
(1, 1), (1, 2),
(2, 1), (2, 3),
(3, 1), (3, 2),
(4, 3), (4, 4),
(5, 5), (5, 3),
(6, 1);

insert into distributie(id_film, id_actor) values
(1, 1), (1, 2),
(2, 3), (2, 2),
(3, 4),
(4, 5), (4, 6),
(5, 7), (5, 8),
(6, 9);

insert into feedback(id_film, continut, nr_stele, nume_autor) values
(1, 'Vizual impresionant, dar povestea e complicata.', 7, 'Ion Popescu'),
(3, 'Cel mai bun film horror din spatiu!', 10, 'AlienFan99'),
(4, 'O poveste emotionanta si trista.', 9, 'Maria D.'),
(5, 'Anthony Hopkins joaca genial.', 10, 'Cinefilul'),
(6, 'Nu am inteles finalul, dar arata bine.', 8, 'Gigel');