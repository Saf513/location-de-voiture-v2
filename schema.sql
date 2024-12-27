--create de data base 
CREATE DATABASE `locationdb`;
--creation de tableau clients
CREATE TABLE `clients` (
    `Num` int NOT NULL AUTO_INCREMENT,
    `Nom` varchar(255) DEFAULT NULL,
    `Adresse` varchar(255) DEFAULT NULL,
    `Tel` varchar(15) DEFAULT NULL,
    PRIMARY KEY (`Num`)
) --creation de tableau voitures
CREATE TABLE `voitures` (
    `NumImmatriculation` varchar(255) NOT NULL,
    `Marque` varchar(255) DEFAULT NULL,
    `Model` varchar(255) DEFAULT NULL,
    `Annee` year DEFAULT NULL,
    PRIMARY KEY (`NumImmatriculation`)
) --creation de tableau contrats
CREATE TABLE `contrat` (
    `NumContrat` int NOT NULL auto_increment,
    `DateDebut` date DEFAULT NULL,
    `DateFin` date DEFAULT NULL,
    `Duree` int DEFAULT NULL,
    `Num` int DEFAULT NULL,
    `NumImmatriculation` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`NumContrat`),
    KEY `Num` (`Num`),
    KEY `NumImmatriculation` (`NumImmatriculation`),
    CONSTRAINT `contrat_ibfk_1` FOREIGN KEY (`Num`) REFERENCES `clients` (`Num`),
    CONSTRAINT `contrat_ibfk_2` FOREIGN KEY (`NumImmatriculation`) REFERENCES `voitures` (`NumImmatriculation`)
) --creation de t ble u de vendeures 
CREATE TABLE vendeurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    date_embauche DATE NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- cretion de Table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'vendor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- creation de table vendors
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nom VARCHAR(255),
    approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
