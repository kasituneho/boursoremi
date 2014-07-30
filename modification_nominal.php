
<?php
//definitions

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";

$action=$argv[1];
$facteur=$argv[2];

if ( is_numeric($facteur))
{
	$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');
	mysql_select_db($base) OR die('Erreur de sélection de la base');
	mysql_query("update dividende set valeur=valeur * $facteur where titre=\"$action\"")OR die('Erreur de la requête MySQL dividende');
	mysql_query("update bna set valeur=valeur * $facteur where titre=\"$action\"")OR die('Erreur de la requête MySQL bna');
	mysql_query("update anc set valeur=valeur * $facteur where titre=\"$action\"")OR die('Erreur de la requête MySQL anc');
}
else
{
 	exit ("\nusage : modification_nominal.php identifiant_action facteur_multiplicatif
        \nexemple : modification_nominal.php SU.PA 0.5
        \n");
}
?>
