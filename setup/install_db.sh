#!/bin/sh
./create_db.sh
./migrate.sh
./rebuild_sps.sh
./create_quick_db_config.sh

