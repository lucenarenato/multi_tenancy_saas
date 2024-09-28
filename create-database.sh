#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS novo_painel;
    GRANT ALL PRIVILEGES ON \`novo_painel%\`.* TO '$MYSQL_USER'@'%';

EOSQL
