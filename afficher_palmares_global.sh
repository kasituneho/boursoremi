#!/bin/bash
USER="bourse"
PASSWD="Bgr@h@m"
BDD="bourse"

case $1 in
	defensif)
	/usr/bin/mysql -u${USER} -p${PASSWD} -D${BDD} $2 -e "select * from palmares where investisseur='defensif' order by gain desc;"
	;;
	entreprenant)
	/usr/bin/mysql -u${USER} -p${PASSWD} -D${BDD} $2 -e "select * from palmares where investisseur!='speculateur' order by gain desc;"
	;;
	speculateur)
	/usr/bin/mysql -u${USER} -p${PASSWD} -D${BDD} $2 -e "select * from palmares order by gain desc;"
	;;
	*)
	echo "usage : afficher_palmares.php PROFIL_INVESTISSEUR [--html]"
	echo "PROFIL_INVESTISSEUR : defensif , entreprenant ou speculateur" 
	exit 0
	;;
esac
