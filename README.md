# verificaasorpresa

## Requisiti

- PHP 8.3 con estensione `pdo_mysql`
- MariaDB (o MySQL)
- Composer

## Installazione dipendenze

Se `vendor/` non e' presente o vuoi reinstallare:

```bash
composer install
```

## Setup database

Le credenziali sono in [src/Database.php](src/Database.php). Per caricare schema e dati:

```bash
sudo service mariadb start
mysql -u utente_phpmyadmin -p < creaDB.sql
mysql -u utente_phpmyadmin -p esercizio_api < popolaDB.sql
```

## Avvio servizi

```bash
./start.sh
```

## Avvio API

```bash
./avviaAPI.sh
```

Nota: `./avviaAPI.sh` usa `/usr/bin/php` per garantire il driver `pdo_mysql`.

## Verifica rapida

```bash
curl http://localhost:8000/
curl http://localhost:8000/1
```

## Installazione completa (opzionale)

Se vuoi installare Apache, PHP, MariaDB e phpMyAdmin in modo automatico:

```bash
./install.sh
```