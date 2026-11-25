CREATE TABLE usuarios(
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
    contr VARCHAR(255) NOT NULL,
    rol ENUM('administrador','invitado', 'policia')
);
INSERT INTO usuarios (nombre_usuario,contr,rol)
VALUES("admin","admin","administrador"),
("policia1","policia1","policia"),
("invitado1","invitado1","invitado");

CREATE TABLE criminales(
    id_criminales INT AUTO_INCREMENT PRIMARY KEY,
    nombre_criminal VARCHAR(100) NOT NULL UNIQUE,
    delito VARCHAR(150) NOT NULL UNIQUE,
    descripcion_delito VARCHAR(255) NOT NULL
);

INSERT INTO criminales (nombre_criminal,delito,descripcion_delito,documento)
VALUES
("Pedro","Malversacion de fondos,Contratos buitres,Corrupcion","Malversacion de fondos,Contratos buitres,COrrupcion","01181099A"),
("Auso","Prostitución infantil","Obligar a menores de edad a hacer contenido sexual","12345612G");