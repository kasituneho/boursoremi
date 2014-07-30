<?php

//definition

$user="boursoremi";
$password="k@bushikitorihikij0u";
$base="boursoremi";
$server="localhost";
$date=time();

// la requete
$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');
mysql_select_db($base) OR die('Erreur de sélection de la base');

//TABLEAU DE BORD

$CONTENU=file_get_contents("/tmp/tableau_de_bord.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"TABLEAU DE BORD\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"TABLEAU DE BORD\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=3";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

//PALMARES DEFENSIF

$CONTENU=file_get_contents("/tmp/palmares_global_defensif.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES DEFENSIF\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES DEFENSIF\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=4";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

//PALMARES ENTREPRENANT

$CONTENU=file_get_contents("/tmp/palmares_global_entreprenant.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES ENTREPRENANT\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES ENTREPRENANT\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=5";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;


//PALMARES INTEGRAL

$CONTENU=file_get_contents("/tmp/palmares_global_integral.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES INTEGRAL\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES INTEGRAL\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=6";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

//PER

$CONTENU=file_get_contents("/tmp/palmares_per.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES PER\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES PER\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=7";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

//PER

$CONTENU=file_get_contents("/tmp/palmares_rendement.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES RENDEMENT\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"PALMARES RENDEMENT\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=8";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

# PORTEFEUILLE DEFENSIF

$CONTENU=file_get_contents("/tmp/portefeuille_defensif.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"portefeuille defensif\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"portefeuille defensif\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=28";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

# PORTEFEUILLE ENTREPRENANT

$CONTENU=file_get_contents("/tmp/portefeuille_entreprenant.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"portefeuille entreprenant\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"portefeuille entreprenant\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=29";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date') ) ;

# VARATION JOUNALIERE

$CONTENU=file_get_contents("/tmp/variation_journaliere.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations journalières\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body variation journaliere') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations journalières\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser variation journaliere') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=34";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date variation journaliere') ) ;


# VARIATION HEBDOMADAIRE

$CONTENU=file_get_contents("/tmp/variation_hebdomadaire.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations hebdomadaires\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body variation hebdomadaire') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations hebdomadaires\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser variation hebdomadaire') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=30";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date variation hebdomadaire') ) ;


# VARIATION MENSUELLE

$CONTENU=file_get_contents("/tmp/variation_mensuelle.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations mensuelles\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body variation mensuelle') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"variations mensuelles\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser variation mensuelle') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=33";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date variation mensuelle') ) ;

# VARIATION ANNUELLE

$CONTENU=file_get_contents("/tmp/variation_annuelle.html");
$requete_body="UPDATE node_revisions SET body='".mysql_real_escape_string($CONTENU)."' WHERE title=\"Variations annuelles\"";
mysql_query("$requete_body") or die( mysql_error('pb execution requete body variation annuelle') ) ;
$requete_teaser="UPDATE node_revisions SET teaser='".mysql_real_escape_string($CONTENU)."' WHERE title=\"Variations annuelles\"";
mysql_query("$requete_teaser") or die( mysql_error('pb execution requete teaser variation annuelle') ) ;
$requete_date="UPDATE node SET created=$date WHERE vid=36";
mysql_query("$requete_date") or die( mysql_error('pb execution requete date variation annuelle') ) ;
mysql_close();
?>
