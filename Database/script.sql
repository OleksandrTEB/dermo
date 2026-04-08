CREATE DATABASE kalendarz2;

USE kalendarz2;

CREATE TABLE term (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    text TEXT NOT NULL,
    type ENUM('zadanie', 'spotkanie') NOT NULL,
    time TIME NULL
);