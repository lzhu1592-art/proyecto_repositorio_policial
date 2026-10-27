CREATE TABLE usuarios(
    id  INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
    contr VARCHAR(255) NOT NULL,
    rol ENUM('administrador','invitado', 'policia')
);
INSERT INTO usuarios (nombre_usuario,contr,rol)
VALUES("admin","123","administrador"),
("policia1","1234","policia"),
("invitado1","12345","invitado");

CREATE TABLE criminales(
    id_criminales INT AUTO_INCREMENT PRIMARY KEY,
    nombre_criminal VARCHAR(100) NOT NULL UNIQUE,
    delito VARCHAR(150) NOT NULL UNIQUE,
    descripcion_delito VARCHAR(255) NOT NULL
);

INSERT INTO criminales (nombre_criminal,delito,descripcion_delito,documento)
VALUES
("juan","Robo","Robo a mano armada","123X"),
("Pepita","Prostitución infantil","Unos de los socios de Epstein","12345Z");