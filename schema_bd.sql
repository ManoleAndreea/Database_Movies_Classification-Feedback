-- test
CREATE DATABASE IF NOT EXISTS feedback_clasificare_filme;
USE feedback_clasificare_filme;

CREATE TABLE detalii_persoana 
(
    id_detalii INT AUTO_INCREMENT PRIMARY KEY,
    data_nasterii DATE NOT NULL,
    nationalitate VARCHAR(50),
    biografie TEXT
);
