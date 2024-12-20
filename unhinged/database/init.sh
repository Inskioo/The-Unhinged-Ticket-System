#!/bin/bash
mysql -u root -p password <<-EOSQL
    CREATE DATABASE IF NOT EXISTS unhinged;
    GRANT ALL PRIVILEGES ON unhinged.* TO 'sail'@'%';
    FLUSH PRIVILEGES;
EOSQL