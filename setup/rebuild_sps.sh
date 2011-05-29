#!/bin/bash
source db_config
for i in `find sp/ -name "*.sql" | sort ` ; do
	echo loading $i ... >&2
	cat $i | $mysql_cmd -h $db_host -u $db_root_user -p$db_root_password $db_name
	if [ $? -gt 0 ] ; then
		exit 1;
	fi
done

