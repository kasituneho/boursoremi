#!/bin/bash

SCRIPT_PATH=/home/bourse/scripts

# Mise à jour des tables de bourse

echo "maj_poids_cac40"
/usr/bin/php $SCRIPT_PATH/maj_poids_cac40.php
echo "maj_palmares_per"
/usr/bin/php $SCRIPT_PATH/maj_palmares_per.php
echo "maj_palmares_rendement"
/usr/bin/php $SCRIPT_PATH/maj_palmares_rendement.php
echo "maj_historique_valeur"
/usr/bin/php $SCRIPT_PATH/maj_historique_valeur.php
echo "maj_palmares_global"
/usr/bin/php $SCRIPT_PATH/maj_palmares_global.php
echo "maj_classement"
/usr/bin/php $SCRIPT_PATH/maj_classement.php

# Mise à jour du site boursoremi

echo "maj_boursoremi"
/usr/bin/php $SCRIPT_PATH/afficher_tableau_de_bord.php > /tmp/tableau_de_bord.html
/usr/bin/php $SCRIPT_PATH/afficher_palmares_global.php defensif > /tmp/palmares_global_defensif.html
/usr/bin/php $SCRIPT_PATH/afficher_palmares_global.php entreprenant > /tmp/palmares_global_entreprenant.html
/usr/bin/php $SCRIPT_PATH/afficher_palmares_global.php speculateur > /tmp/palmares_global_integral.html
/usr/bin/php $SCRIPT_PATH/afficher_palmares_per.php > /tmp/palmares_per.html
/usr/bin/php $SCRIPT_PATH/afficher_palmares_rendement.php > /tmp/palmares_rendement.html
/usr/bin/php $SCRIPT_PATH/afficher_portefeuille.php defensif > /tmp/portefeuille_defensif.html
/usr/bin/php $SCRIPT_PATH/afficher_portefeuille.php entreprenant > /tmp/portefeuille_entreprenant.html
/usr/bin/php $SCRIPT_PATH/afficher_variation.php journaliere > /tmp/variation_journaliere.html
/usr/bin/php $SCRIPT_PATH/afficher_variation.php hebdomadaire > /tmp/variation_hebdomadaire.html
/usr/bin/php $SCRIPT_PATH/afficher_variation.php mensuelle > /tmp/variation_mensuelle.html
/usr/bin/php $SCRIPT_PATH/afficher_variation.php annuelle > /tmp/variation_annuelle.html
/usr/bin/php $SCRIPT_PATH/maj_boursoremi.php
