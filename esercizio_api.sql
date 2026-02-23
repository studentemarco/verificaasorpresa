-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Feb 23, 2026 alle 08:59
-- Versione del server: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versione PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esercizio_api`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Catalogo`
--

CREATE TABLE `Catalogo` (
  `fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `costo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Catalogo`
--

INSERT INTO `Catalogo` (`fid`, `pid`, `costo`) VALUES
(1, 1, 0.15),
(1, 2, 0.30),
(2, 1, 0.18),
(4, 1, 1.00),
(4, 2, 1.00),
(4, 4, 12.50),
(4, 5, 1.20),
(4, 6, 1.00),
(4, 7, 12.00),
(4, 8, 0.50),
(5, 5, 1.50),
(5, 6, 5.00),
(6, 4, 13.00),
(6, 6, 4.80),
(7, 4, 11.50),
(7, 5, 1.10),
(7, 7, 2.50),
(7, 8, 0.30),
(8, 5, 1.25),
(8, 8, 0.35);

-- --------------------------------------------------------

--
-- Struttura della tabella `Fornitori`
--

CREATE TABLE `Fornitori` (
  `fid` int(11) NOT NULL,
  `fnome` varchar(100) NOT NULL,
  `indirizzo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Fornitori`
--

INSERT INTO `Fornitori` (`fid`, `fnome`, `indirizzo`) VALUES
(1, 'Ferramenta Rossi', 'Via Roma 10'),
(2, 'Utensili Bianchi', 'Via Milano 25'),
(4, 'BetaTech', 'Via Roma 22, Milano'),
(5, 'Gamma Industries', 'Corso Torino 10, Torino'),
(6, 'Delta Solutions', 'Viale Firenze 33, Firenze'),
(7, 'Acme', 'Via Roma 50, Milano'),
(8, 'RedTech', 'Via dei Colori 1, Milano');

-- --------------------------------------------------------

--
-- Struttura della tabella `Pezzi`
--

CREATE TABLE `Pezzi` (
  `pid` int(11) NOT NULL,
  `pnome` varchar(100) NOT NULL,
  `colore` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `Pezzi`
--

INSERT INTO `Pezzi` (`pid`, `pnome`, `colore`) VALUES
(1, 'Vite', 'argento'),
(2, 'Bullone', 'nero'),
(4, 'Circuito Stampato', 'giallo'),
(5, 'LED Rosso', 'rosso'),
(6, 'Sensore Temperatura', 'verde'),
(7, 'Microchip', 'nero'),
(8, 'Resistenza', 'rosso');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Catalogo`
--
ALTER TABLE `Catalogo`
  ADD PRIMARY KEY (`fid`,`pid`),
  ADD KEY `fk_catalogo_pezzo` (`pid`);

--
-- Indici per le tabelle `Fornitori`
--
ALTER TABLE `Fornitori`
  ADD PRIMARY KEY (`fid`);

--
-- Indici per le tabelle `Pezzi`
--
ALTER TABLE `Pezzi`
  ADD PRIMARY KEY (`pid`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Catalogo`
--
ALTER TABLE `Catalogo`
  ADD CONSTRAINT `fk_catalogo_fornitore` FOREIGN KEY (`fid`) REFERENCES `Fornitori` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_catalogo_pezzo` FOREIGN KEY (`pid`) REFERENCES `Pezzi` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
