#!/bin/bash

/usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
    echo "=> Waiting for confirmation of MySQL service startup"
    sleep 5
    mysql -uroot -e "status" > /dev/null 2>&1
    RET=$?
done

PASS=${MYSQL_ADMIN_PASS:-$(pwgen -s 12 1)}
_word=$( [ ${MYSQL_ADMIN_PASS} ] && echo "preset" || echo "random" )
echo "=> Creating MySQL admin user with ${_word} password"

mysql -uroot -e "CREATE USER 'admin'@'%' IDENTIFIED BY '$PASS'"
mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' WITH GRANT OPTION"

mysql -uroot -e " GRANT ALL PRIVILEGES ON phpmyadmin.* TO  'pma'@'localhost' IDENTIFIED BY ''"

CREATE_MYSQL_USER=false

if [ -n "$CREATE_MYSQL_BASIC_USER_AND_DB" ] || \
   [ -n "$MYSQL_USER_NAME" ] || \
   [ -n "$MYSQL_USER_DB" ] || \
   [ -n "$MYSQL_USER_PASS" ]; then
      CREATE_MYSQL_USER=true
fi

if [ "$CREATE_MYSQL_USER" = true ]; then
    _user=${MYSQL_USER_NAME:-user}
    _userdb=${MYSQL_USER_DB:-db}
    _userpass=${MYSQL_USER_PASS:-password}

    mysql -uroot -e "CREATE USER '${_user}'@'%' IDENTIFIED BY  '${_userpass}'"
    mysql -uroot -e "GRANT USAGE ON *.* TO  '${_user}'@'%' IDENTIFIED BY '${_userpass}'"
    mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ${_userdb}"
    mysql -uroot -e "GRANT ALL PRIVILEGES ON ${_userdb}.* TO '${_user}'@'%'"
fi

echo "=> Done!"

echo "========================================================================"
echo "You can now connect to this MySQL Server with $PASS"
echo ""
echo "    mysql -uadmin -p$PASS -h<host> -P<port>"
echo ""
echo "Please remember to change the above password as soon as possible!"
echo "MySQL user 'root' has no password but only allows local connections"
echo ""

if [ "$CREATE_MYSQL_USER" = true ]; then
    echo "We also created"
    echo "A database called '${_userdb}' and"
    echo "a user called '${_user}' with password '${_userpass}'"
    echo "'${_user}' has full access on '${_userdb}'"
fi

echo "=> Nghĩa set new..."
    # mysql -uroot -e "CREATE USER IF NOT EXISTS  'root'@'%' IDENTIFIED WITH mysql_native_password BY 'nghia@tv'"
    # mysql -uroot -e "ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'nghia@tv'"
    # mysql -uroot -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'nghia@tv'"
    # mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION"
    # mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION"
    # mysql -uroot -e "FLUSH PRIVILEGES"
    # mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ketoan_xuanmai"
    # mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ketoan_upload"
    # mysql -uroot -e "SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION'"
    # mysql -uroot -e "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'"
    # mysql -uroot -e "SET GLOBAL log_bin_trust_function_creators = 1" 
    # mysql -uroot -e "SET SESSION log_bin_trust_function_creators = 1" 
echo "=============>"

echo "enjoy!"
echo "========================================================================"

mysqladmin -uroot shutdown
