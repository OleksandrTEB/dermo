CREATE DATABASE kalendarz;

USE kalendarz;

CREATE TABLE zadania
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    data_zadania  DATE DEFAULT CURRENT_TIMESTAMP NOT NULL,
    text          TEXT NOT NULL
)