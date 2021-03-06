drop table if exists paragraphs;
drop table if exists users;
drop table if exists domains;

CREATE TABLE domains(
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    principal BOOLEAN DEFAULT FALSE
);

CREATE TABLE users(
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(250) NOT NULL,
    domain_id INTEGER NOT NULL,

    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE
);

CREATE TABLE paragraphs(
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    content TEXT NOT NULL,
    domain_id INTEGER NOT NULL,

    FOREIGN KEY (domain_id) REFERENCES domains(id) ON DELETE CASCADE
);
