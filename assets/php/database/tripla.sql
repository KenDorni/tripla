
DROP DATABASE IF EXISTS tripla;
CREATE DATABASE tripla;

DROP TABLE IF EXISTS tripla.Iternary_Transit;
DROP TABLE IF EXISTS tripla.Iternary_Stop;
DROP TABLE IF EXISTS tripla.Iternary;
DROP TABLE IF EXISTS tripla.User;

CREATE TABLE tripla.User (
    pk_user INT AUTO_INCREMENT NOT NULL,
    email_address VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    username VARCHAR(64),
    PRIMARY KEY (pk_user)
);

CREATE TABLE tripla.Iternary (
    pk_itinerary INT AUTO_INCREMENT NOT NULL,
    creation_date TIMESTAMP NOT NULL,
    fk_user_created INT NOT NULL,
    PRIMARY KEY (pk_itinerary),
    FOREIGN KEY (fk_user_created) REFERENCES User (pk_user)
);

CREATE TABLE tripla.Iternary_Stop (
    pk_itinerary_stop INT AUTO_INCREMENT NOT NULL,
    type ENUM('Location','Stay','Activity') NOT NULL,
    value VARCHAR(256) NOT NULL,
    booking_ref VARCHAR(32) NOT NULL,
    link VARCHAR(2048) NOT NULL,
    online_ticket BLOB NOT NULL,
    start DATETIME NOT NULL,
    stop DATETIME NOT NULL,
    fk_itinerary_includes INT NOT NULL,
    PRIMARY KEY (pk_itinerary_stop),
    FOREIGN KEY (fk_itinerary_includes) REFERENCES Iternary (pk_itinerary)
);
CREATE TABLE tripla.Iternary_Transit (
    pk_itinerary_transit INT AUTO_INCREMENT NOT NULL,
    method VARCHAR(64) NOT NULL,
    booking_ref VARCHAR(32) NOT NULL,
    link VARCHAR(2048) NOT NULL,
    online_ticket BLOB NOT NULL,
    start DATETIME NOT NULL,
    stop DATETIME NOT NULL,
    fk_before INT NOT NULL,
    fk_after INT NOT NULL,
    fk_itinerary_has_assigned INT NOT NULL,
    PRIMARY KEY (pk_itinerary_transit),
    FOREIGN KEY (fk_before) REFERENCES Iternary_Stop (pk_itinerary_stop),
    FOREIGN KEY (fk_after) REFERENCES Iternary_Stop (pk_itinerary_stop),
    FOREIGN KEY (fk_itinerary_has_assigned) REFERENCES Iternary (pk_itinerary)
);


-- Random generated values

-- Insert Users
INSERT INTO tripla.User (email_address, password, username) VALUES
('john.doe@example.com', 'hashed_password_1', 'JohnDoe'),
('jane.smith@example.com', 'hashed_password_2', 'JaneSmith'),
('alice.brown@example.com', 'hashed_password_3', 'AliceBrown');

-- Insert Itineraries
INSERT INTO tripla.Iternary (creation_date, fk_user_created) VALUES
('2025-03-01 08:30:00', 1),
('2025-03-02 14:15:00', 2),
('2025-03-03 19:45:00', 3);

-- Insert Itinerary Stops
INSERT INTO tripla.Iternary_Stop (type, value, booking_ref, link, online_ticket, start, stop, fk_itinerary_includes) VALUES
('Location', 'Eiffel Tower, Paris', 'REF123', 'https://tickets.paris.fr', 'binary_data', '2025-03-05 10:00:00', '2025-03-05 12:00:00', 1),
('Stay', 'Hotel Plaza, Madrid', 'REF456', 'https://hotelbooking.com', 'binary_data', '2025-03-06 15:00:00', '2025-03-07 11:00:00', 1),
('Activity', 'Museum Visit, Berlin', 'REF789', 'https://museumtickets.de', 'binary_data', '2025-03-08 13:00:00', '2025-03-08 16:00:00', 2),
('Location', 'Colosseum, Rome', 'REF321', 'https://rome-tickets.com', 'binary_data', '2025-03-09 09:00:00', '2025-03-09 11:30:00', 3),
('Stay', 'Beach Resort, Ibiza', 'REF654', 'https://resortbooking.com', 'binary_data', '2025-03-10 17:00:00', '2025-03-12 10:00:00', 3);

-- Insert Itinerary Transits
INSERT INTO tripla.Iternary_Transit (method, booking_ref, link, online_ticket, start, stop, fk_before, fk_after, fk_itinerary_has_assigned) VALUES
('Train', 'TR12345', 'https://trainbooking.com', 'binary_data', '2025-03-05 13:00:00', '2025-03-05 16:00:00', 1, 2, 1),
('Flight', 'FL98765', 'https://airline.com', 'binary_data', '2025-03-07 12:00:00', '2025-03-07 15:30:00', 2, 3, 2),
('Bus', 'BUS45678', 'https://buscompany.com', 'binary_data', '2025-03-08 17:00:00', '2025-03-08 22:00:00', 3, 4, 3),
('Boat', 'BOAT123', 'https://ferrybooking.com', 'binary_data', '2025-03-09 12:30:00', '2025-03-09 18:00:00', 4, 5, 3);
