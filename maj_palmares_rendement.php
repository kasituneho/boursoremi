<?php 

function get_quote($ticker)
{
   $fd = fopen ("http://quote.yahoo.com/d/quotes.csv?s=".$ticker."&f=sl1d1t1c1ohgv&e=.csv", "r");
   $contents = fread($fd, 200);
   fclose($fd);
   $contents = str_replace("\"", "", $contents);
   $contents = explode(",", $contents);
   return $contents[1];
}
//definitions

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$annee=date("Y");
$annee_fin=$annee -1;

//liste des requetes


//main

$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');     
mysql_select_db($base) OR die('Erreur de sélection de la base'); 	
$requete_action = mysql_query("select titre from action where active=1")OR die('Erreur de la requête MySQL titre'); 
while($row_action = mysql_fetch_array($requete_action))
 {
	$action=$row_action["titre"];
	$prix=0;
	$prix=get_quote($action);
	for($temps=1;$temps<=10;$temps++)
	{
		$annee_debut=$annee -$temps -2;
		$requete_DIV = mysql_query("select valeur  from dividende where annee < \"$annee_fin\"  and annee > \"$annee_debut\" and titre=\"$action\" ")
		OR die('Erreur de la requête MySQL DIV');	
		$DIV=0;
		while($row_dividende = mysql_fetch_array($requete_DIV))	
		{
			$DIV+=$row_dividende["valeur"];	
		}
		$DIV=$DIV/$temps;
		$REND=$DIV/$prix;
		$REND=round($REND, "5");
		$valeur_rendement[$temps]=$REND;
		$requete_existe=mysql_query("select * from rendement where titre=\"$action\" and temps=\"$temps\" ");
		$rendement_existe=mysql_num_rows($requete_existe);
		if ( $rendement_existe != 0)
		{
			$requete_rendement = "update rendement set valeur=$REND where titre=\"$action\" and temps=\"$temps\" ";
		}
		else
		{
  			$requete_rendement = "INSERT  INTO  rendement (titre,temps,valeur)
 			VALUES ('$action','$temps','$REND')";
		}
                echo "$action $temps $REND \n";
		$requete = mysql_query($requete_rendement) or die( mysql_error() ) ;
	}
	

}
mysql_close();
?> 
