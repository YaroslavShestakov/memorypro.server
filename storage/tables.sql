CREATE TABLE users (
    id          INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    email       VARCHAR(50) NOT NULL,
    password    VARCHAR(32) NOT NULL,
    firstname   VARCHAR(30) NOT NULL,
    lastname    VARCHAR(30),
    phone       VARCHAR(20),

    UNIQUE(email)
) Engine = InnoDB;