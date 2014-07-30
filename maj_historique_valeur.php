<?php 

############################################################################################
#
# Calcul de la valeur d'une action en fonction de son BNA et divdidende (de 1 à 10 ans) 
#
# valeur en fonction du dividende	: (sum(dividende)/temps) * ( 21 + 3 * temps) 
# valeur en fonction du bna		: (sum(bna)/temps) * ( 10,5 + 1,5 * temps)
#
############################################################################################

//definitions

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$annee=date("Y");
$annee_fin=$annee;

//main

$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');     
mysql_select_db($base) OR die('Erreur de sélection de la base'); 	
$requete_action = mysql_query("select distinct titre  from action where active=1 order by titre asc")OR die('Erreur de la requête MySQL action'); 
while($row_titre = mysql_fetch_array($requete_action))
 {
	$action=$row_titre["titre"];
	for($temps=1;$temps<=10;$temps++)
	{
		$annee_debut=$annee -$temps -1;
		$BNA=0;
		$requete_BNA = mysql_query("select valeur  from bna where annee < \"$annee_fin\"  and annee > \"$annee_debut\" and titre=\"$action\" ")
		OR die('Erreur de la requête MySQL BNA');	
		while($row_bna = mysql_fetch_array($requete_BNA))	
		{
			$BNA+=$row_bna["valeur"];	
		}
		$dividende=0;
		$requete_dividende = mysql_query("select valeur  from dividende where annee < \"$annee_fin\"  and annee > \"$annee_debut\" and titre=\"$action\" ")
		OR die('Erreur de la requête MySQL dividende');	
		while($row_dividende = mysql_fetch_array($requete_dividende))	
		{
			$dividende+=$row_dividende["valeur"];	
		}
		$BNA_moyen=$BNA/$temps;
		$dividende_moyen=$dividende/$temps;
		$BNA_valeur=$BNA_moyen * (10.5 + (1.5 * $temps));
		$dividende_valeur=$dividende_moyen * (21 + (3 * $temps)) ;
		$total_valeur=($BNA_valeur + $dividende_valeur)/2;
		$requete_existe=mysql_query("select * from valeur where titre=\"$action\" and temps=\"$temps\" ");
		$valeur_existe=mysql_num_rows($requete_existe);
		if ($valeur_existe != 0)
		{
			$requete_valeur = "update valeur set BNA=$BNA_valeur , dividende=$dividende_valeur, TOTAL=$total_valeur where titre=\"$action\" and temps=\"$temps\" ";
		}
		else
		{
  			$requete_valeur = "INSERT  INTO  valeur
 			VALUES ('$action','$temps','$BNA_valeur','$dividende_valeur','$total_valeur') " ;
		}
	        mysql_query($requete_valeur) OR die('Erreur de la requête MySQL valeur');
	}
	

}
mysql_close();
?> 
