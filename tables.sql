CREATE SCHEMA IF NOT EXISTS FireBet DEFAULT CHARACTER SET utf8;
USE FireBet;

DROP TABLE IF EXISTS student_group;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS bets;
DROP TABLE IF EXISTS `groups`;
DROP TABLE IF EXISTS student_vbank;
DROP TABLE IF EXISTS vbanks;
DROP TABLE IF EXISTS students;

CREATE TABLE IF NOT EXISTS students (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  evaluation INT NOT NULL,
  description VARCHAR(255) NULL,
  role ENUM('user', 'manager', 'admin') NOT NULL DEFAULT 'user',
  email VARCHAR(100) NULL,
  password VARCHAR(255) NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS vbanks (
  id INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(45) NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `groups` (
  id INT NOT NULL AUTO_INCREMENT,
  vbank_id INT NOT NULL,
  leader_id INT NOT NULL, 
  grade INT DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (vbank_id)
    REFERENCES vbanks (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (leader_id)
    REFERENCES students (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS student_group (
  student_id INT NOT NULL,
  group_id INT NOT NULL,
  FOREIGN KEY (student_id)
    REFERENCES students (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (group_id)
    REFERENCES `groups` (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
  id INT NOT NULL AUTO_INCREMENT,
  author_id INT NOT NULL,
  vbank_id INT NOT NULL,
  content VARCHAR(500) NOT NULL,
  date DATE NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (author_id)
    REFERENCES students (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (vbank_id)
    REFERENCES vbanks (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
  author_id INT NOT NULL,
  comment_id INT NOT NULL, 
  FOREIGN KEY (author_id)
    REFERENCES students (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (comment_id)
    REFERENCES comments (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- nwm czy nie dać tu vbank_id
CREATE TABLE IF NOT EXISTS bets (
  id INT NOT NULL AUTO_INCREMENT,
  student_id INT NOT NULL,
  amount REAL NOT NULL,
  profit_loss REAL DEFAULT NULL,
  status ENUM('in_process', 'payed', 'denied', 'cashed') NOT NULL DEFAULT 'in_process',
  group_id INT NOT NULL,
  
  PRIMARY KEY (id),
  FOREIGN KEY (student_id)
    REFERENCES students (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (group_id)
    REFERENCES `groups` (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
