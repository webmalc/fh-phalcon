#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
CONFIG=$DIR'/../app/config/config.ini'
DB=$(awk -F "=" '/dbName/ {print $2}' $CONFIG)
USER=$(awk -F "=" '/dbUsername/ {print $2}' $CONFIG)
PASSWORD=$(awk -F "=" '/dbPassword/ {print $2}' $CONFIG)
GREEN='\e[0;32m'
NC='\e[0m'
DUMP_SCHEMA=$DIR/'sql/schema.sql'
DUMP_DATA=$DIR/'sql/data.sql'

echo -e "${GREEN}Database name: $DB. Username: $USER. Password: $PASSWORD${NC}"

if [[ $1 == 'restore' ]]; then

    if [[ $2 == 'data' ]]; then
        echo -e "${GREEN}Restore postgresql db dump schema & data${NC}"
        psql -U $USER $DB < $DUMP_DATA
        exit
    fi

    echo -e "${GREEN}Restore postgresql db schema${NC}"
    psql -U $USER $DB < $DUMP_SCHEMA
    exit
fi

if [[ $1 == 'data' ]]; then
    echo -e "${GREEN}Dump postgresql schema & data${NC}"
    pg_dump -U $USER --clean $DB > $DUMP_DATA
    exit
fi

echo -e "${GREEN}Dump postgresql schema${NC}"
pg_dump -U $USER --schema-only --clean $DB > $DUMP_SCHEMA