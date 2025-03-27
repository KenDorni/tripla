DROP DATABASE IF EXISTS tripla;
CREATE DATABASE tripla;

DROP TABLE IF EXISTS tripla.Itinerary_Transit;
DROP TABLE IF EXISTS tripla.Itinerary_Stop;
DROP TABLE IF EXISTS tripla.Itinerary;
DROP TABLE IF EXISTS tripla.User;

CREATE TABLE tripla.User (
    pk_user INT AUTO_INCREMENT NOT NULL,
    email_address VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    username VARCHAR(64),
    PRIMARY KEY (pk_user)
);

CREATE TABLE tripla.Itinerary (
    pk_itinerary INT AUTO_INCREMENT NOT NULL,
    creation_date TIMESTAMP NOT NULL,
    fk_user_created INT NOT NULL,
    PRIMARY KEY (pk_itinerary),
    FOREIGN KEY (fk_user_created) REFERENCES User (pk_user) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE tripla.itinerary_stop (
    pk_itinerary_stop INT AUTO_INCREMENT PRIMARY KEY,
    fk_itinerary_includes INT NOT NULL,
    type VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    booking_ref VARCHAR(255),
    link VARCHAR(255),
    online_ticket BLOB,
    start DATETIME NOT NULL,
    stop DATETIME NOT NULL,
    FOREIGN KEY (fk_itinerary_includes) REFERENCES Itinerary (pk_itinerary) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE tripla.Itinerary_Transit (
    pk_itinerary_transit INT AUTO_INCREMENT NOT NULL,
    method VARCHAR(64) NOT NULL,
    booking_ref VARCHAR(32) NOT NULL,
    link VARCHAR(2048) NOT NULL,
    online_ticket VARCHAR(12) NOT NULL,
    start DATETIME NOT NULL,
    stop DATETIME NOT NULL,
    fk_itinerary_has_assigned INT NOT NULL,
    PRIMARY KEY (pk_itinerary_transit),
    FOREIGN KEY (fk_itinerary_has_assigned) REFERENCES Itinerary (pk_itinerary) ON DELETE CASCADE ON UPDATE CASCADE
);
