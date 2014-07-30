#!/bin/bash
USER="bourse"
PASSWD="Bgr@h@m"
BDD="bourse"
TABLE=$1
if [ -z "$TABLE" ]
then
	usage afficher_palmares TABLE
else
	/usr/bin/mysql -u${USER} -p${PASSWD} -D${BDD} $2 -e "select * from $TABLE ;"
fi
