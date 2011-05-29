#!/bin/bash

# shell script for creating the database
# this script will create the database using the connection as defined
# in db_config. You can copy db_config.sample into this file and adapt it
# to your settings

source db_config

cat <<EOF | $mysql_cmd -h $db_host -u $db_root_user -p$db_root_password
CREATE DATABASE IF NOT EXISTS $db_name;
USE $db_name;
CREATE TABLE db_migrations_performed(
	version_number INT NOT NULL,
	filename VARCHAR(255),
	PRIMARY KEY(version_number)
);

EOF

