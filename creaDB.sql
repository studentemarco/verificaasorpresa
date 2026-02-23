-- ============================================
-- Creazione Database
-- ============================================

DROP DATABASE IF EXISTS esercizio_api;
CREATE DATABASE esercizio_api
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE esercizio_api;

-- ============================================
-- Tabella: Fornitori
-- ============================================

CREATE TABLE Fornitori (
    fid INT NOT NULL,
    fnome VARCHAR(100) NOT NULL,
    indirizzo VARCHAR(255) NOT NULL,
    PRIMARY KEY (fid)
) ENGINE=InnoDB;

-- ============================================
-- Tabella: Pezzi
-- ============================================

CREATE TABLE Pezzi (
    pid INT NOT NULL,
    pnome VARCHAR(100) NOT NULL,
    colore VARCHAR(50) NOT NULL,
    PRIMARY KEY (pid)
) ENGINE=InnoDB;

-- ============================================
-- Tabella: Catalogo
-- ============================================

CREATE TABLE Catalogo (
    fid INT NOT NULL,
    pid INT NOT NULL,
    costo DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (fid, pid),

    CONSTRAINT fk_catalogo_fornitore
        FOREIGN KEY (fid)
        REFERENCES Fornitori(fid)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_catalogo_pezzo
        FOREIGN KEY (pid)
        REFERENCES Pezzi(pid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;