#!/bin/bash

export USER='bourse'
export PASSWORD='Bgr@h@m'
export BASE="bourse"
SERVER="localhost"
export ANNEE=$(date +%Y)
export ANNEE_PRECEDENTE=$(date --date="-1 year" +%Y)

echo "maj gearing"

/usr/bin/mysql -u $USER -p"$PASSWORD" -D $BASE -e "insert into gearing (annee,titre,valeur) select $ANNEE,titre,valeur from gearing where annee = $ANNEE_PRECEDENTE";

echo "price book ratio"

/usr/bin/mysql -u $USER -p"$PASSWORD" -D $BASE -e "insert into anc (annee,titre,valeur) select $ANNEE,titre,valeur from anc where annee = $ANNEE_PRECEDENTE";

echo "maj_prevision_boursorama.php"
php /home/bourse/scripts/maj_prevision_boursorama.php
