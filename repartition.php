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

$CAC40 = get_quote('^FCHI');

	$annee=date("Y");
	$libre=0;
	$total=0;
	$connexion = mysql_connect('localhost', 'root', 'supcha&1') OR die('Erreur de connexion');     
	mysql_select_db('finance') OR die('Erreur de sélection de la base'); 	
	$requete_libre = mysql_query("select (valeur)  from bilan_actif where annee = \"$annee\"  and bloque=0")OR die('Erreur de la requête MySQL'); 
    	while($row = mysql_fetch_array($requete_libre)) {
		$libre+=$row[(valeur)];
	}
	$requete_total = mysql_query("select (valeur)  from bilan_actif where annee = \"$annee\" ")OR die('Erreur de la requête MySQL'); 
    	while($row = mysql_fetch_array($requete_total)) {
		$total+=$row[(valeur)];
	}
	echo "CAC40 : " . $CAC40;
	echo "\n";
	echo "disponible : " . $libre;
	echo "\n";
	echo "total : " . $total;
	echo "\n";
	$ratio=$libre/$total;
	echo "ratio disponible : " . $ratio;
	echo "\n";
	$ratio_surplus=$ratio-($CAC40 / 10000);
	echo "ratio_surplus : " . $ratio_surplus;
	echo "\n";
	if ($ratio_surplus < 0)
	{
		$vente=$total * ( - $ratio_surplus);
		echo "vendre pour : " . $vente;
		echo "\n";
	}
	else
	{
		$achat=$total * ($ratio_surplus);
                echo "acheter pour : " . $achat;
                echo "\n";	
	}
	mysql_close();
?> 
