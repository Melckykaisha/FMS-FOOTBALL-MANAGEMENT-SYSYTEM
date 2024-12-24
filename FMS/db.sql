CREATE DATABASE tournament_db;

USE tournament_db;

CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) NOT NULL,
    stadium_name VARCHAR(100)
);


CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    round INT NOT NULL,
    home_team VARCHAR(100) NOT NULL,
    away_team VARCHAR(100) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE scores (
    score_id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    home_score INT NOT NULL,
    away_score INT NOT NULL,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
