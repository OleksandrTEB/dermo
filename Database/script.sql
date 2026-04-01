CREATE DATABASE kalendarz2;

USE kalendarz2;

CREATE TABLE zadania
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    data_zadania  DATE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    text          TEXT NOT NULL
);

CREATE TABLE spotkania
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    data_spotkania  DATE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    text          TEXT NOT NULL
)