#!/bin/bash
# shell skript for the migration

tmpmaker=mktemp
source db_config

# determine the current version
last_migration=`$mysql_cmd -e "
SELECT IF(MAX(version_number) IS NULL, -1, MAX(version_number)) FROM db_migrations_performed;" -B -N -h $db_host -u $db_root_user -p$db_root_password $db_name`

tmpfile=`$tmpmaker`
for i in *.sql ; do echo $i >> $tmpfile ; done
migration_files=`cat $tmpfile | sort`
rm $tmpfile

for i in $migration_files ; do
	test_nr=`echo $i | sed 's/\(^[0-9]*\).*/\1/'`
	if [ $test_nr -gt $last_migration ] ; then
		echo Migrating $i...
		(cat $i ; echo "INSERT INTO db_migrations_performed(version_number, filename) VALUES ($test_nr, '$i');" ) | $mysql_cmd -h $db_host -u $db_root_user -p$db_root_password --default-character-set=utf8 $db_name
		if [ $? -gt 0 ] ; then
			exit 1;
		fi
	fi
done

