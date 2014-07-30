#!/bin/bash
USER="bourse"
PASSWD="Bgr@h@m"
BDD="bourse"

NB_JOUR=$1

if [ -z $NB_JOUR ]
then
	echo "	usage  : afficher_variation.sh  NOMBRE_DE_JOURS [--html]"
	exit 1
fi

if [ ! -z  $2 ]
then
	HTML=$2
fi

/usr/bin/mysql -u${USER} -p${PASSWD} -D${BDD} $HTML -e "select ja.titre as titre,(jj.classement - ja.classement) as hebdomadaire  from classement as jj, classement as ja where  TO_DAYS(CURDATE()) - TO_DAYS(jj.jour) = $NB_JOUR and TO_DAYS(CURDATE()) - TO_DAYS(ja.jour) = 0 and ja.titre=jj.titre order by hebdomadaire desc;"
