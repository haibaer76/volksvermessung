#!/bin/bash
# creates a quick and dirty config file

source db_config

cat <<EOF > $htdocs_path/db_config.php
<?php
// generated configuration file. Do not modify!
define('APP_DB_HOST', '$db_host');
define('APP_DB_NAME', '$db_name');
define('APP_DB_USER', '$db_root_user');
define('APP_DB_PASS', '$db_root_password');
define('APP_TMVC_DIR', '$tinymvc_path');
define('APP_SMARTY_DIR', '$htdocs_path/smarty');
define('APP_BASE_URL', '$relative_uri');
define('APP_HOST', '$app_host');
define('APP_QUESTION_FILE', '$question_file');
EOF

echo -n "?>" >> $htdocs_path/db_config.php

