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
		$requete_BNA = mysql_query("select valeur  from bna where annee < \"$annee_fin\"  and annee > \"$annee_debut\" and titre=\"$action\" ")
		OR die('Erreur de la requête MySQL BNA');	
		$BNA=0;
		while($row_bna = mysql_fetch_array($requete_BNA))	
		{
			$BNA+=$row_bna["valeur"];	
		}
		$BNA=$BNA/$temps;
		$PER=$prix/$BNA;
		$PER=round($PER, "2");
		if ($PER <= 0)
                {
			$PER=100;
		}
                if ($PER > 100)
                {
                	$PER=100;
                }
		$valeur_per[$temps]=$PER;
		$requete_existe=mysql_query("select * from per where titre=\"$action\" and temps=\"$temps\" ");
		$per_existe=mysql_num_rows($requete_existe);
		if ( $per_existe != 0)
		{
			$requete_per = "update per set valeur=$PER where titre=\"$action\" and temps=\"$temps\" ";
		}
		else
		{
  			$requete_per = "INSERT  INTO  per (titre,temps,valeur)
 			VALUES ('$action','$temps','$PER')";
		}
		echo "$action $temps $PER \n";
		$requete = mysql_query($requete_per) or die( mysql_error() ) ;
	}
	

}
mysql_close();
?> 
