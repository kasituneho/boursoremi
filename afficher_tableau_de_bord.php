<?php


// initialisation des taux

$taux_livret_A=0.0125;
$taux_cgs_rds=0.155;
$taux_impot=0.24;

//initialisatin cac40

function get_quote($ticker)
{
   $fd = fopen ("http://quote.yahoo.com/d/quotes.csv?s=".$ticker."&f=sl1d1t1c1ohgv&e=.csv", "r");
   $contents = fread($fd, 200);
   fclose($fd);
   $contents = str_replace("\"", "", $contents);
   $contents = explode(",", $contents);
   return $contents[1];
}

// initialisation OAT

$html_contents = file_get_contents('http://www.aft.gouv.fr/articles/tec-10-du-jour_7124.html');
#print_r ($html_contents);

#$pattern='/.*Indice Tec 10 au.*%/';
$pattern='/.*Tec 10 index at.*/';

if (preg_match($pattern, $html_contents, $matches))
{
        #$matches2 = preg_split('/%/',$matches[0],-1,PREG_SPLIT_OFFSET_CAPTURE);
        #$matches3 = preg_split('/ /',$matches[0][0],-1,PREG_SPLIT_OFFSET_CAPTURE);
	#print_r ($matches3);
        #$pourcent_oat = $matches3[2][0];
	$matches2=explode(" ",$matches[0]);
        $pourcent_oat = $matches2[7];
	$taux_oat = $pourcent_oat / 100;
}
else
{
        exit("impossible de trouve le taux des OAT");
}

if (!is_numeric($taux_oat))
{
	echo $taux_oat;
	exit() ;
}


// inialisation des actions


$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de sélection de la base');
$requete_poids=mysql_query("select titre,poids from action where CAC40=1;")OR die('Erreur de la requête MySQL');

// debut du tableau

echo '<table bgcolor="#FFFFFF">'."\n";

// entete du tableau
echo '<tr>';
echo '<td bgcolor="#669999"><b><u>Methode</u></b></td>';
echo '<td bgcolor="#669999"><b><u>% du portefeuille en action</u></b></td>';
echo '<td bgcolor="#669999"><b><u>Description</u></b></td>';
echo '</tr>'."\n";

$rendement=0;
$valeur_CAC40=0;
$benefice_CAC40=0;
$poids_total=0;

// contenu du tableau

while($row_poids = mysql_fetch_array($requete_poids)) 
{
	$action=$row_poids["titre"];
	$poids=$row_poids["poids"];
	$requete_rendement=mysql_query("select valeur from rendement where temps=1 and titre=\"$action\" " )OR die('Erreur de la requête rendment');
	while($row_rendement = mysql_fetch_array($requete_rendement))
	{
		$rendement+=($row_rendement["valeur"] * $row_poids["poids"]);
	}
	$requete_palmares=mysql_query("select titre,prix,valeur from palmares  where titre=\"$action\" " )OR die('Erreur de la requête palmares');
	while($row_palmares = mysql_fetch_array($requete_palmares))
	{
#		echo $row_palmares["titre"]." ".$row_palmares["prix"]." ".$row_poids["poids"]."\n";
		$valeur_CAC40+=($row_palmares["prix"] * $row_poids["poids"]);
		$benefice_CAC40+=($row_palmares["valeur"] * $row_poids["poids"]);
		
	}
	$poids_total+=$row_poids["poids"];	
}


mysql_close();

// verification

/*
if ($poids_total eq "1")
{
	exit("poids : " .  $poids_total);
}
*/


$rendement_net=$rendement*(1 - $taux_cgs_rds);
$pourcentage_action= round (100 * $rendement_net,5) ;

//methode livret A

$repartition=round (100 * ($rendement_net / ($rendement_net + $taux_livret_A)),5);
$pourcentage_livret_A= 100 * $taux_livret_A;

echo '<tr>';
echo '<td bgcolor="#CCCCCC">comparaison Livret A</td>';
echo '<td bgcolor="#CCCCCC">'.$repartition.' %</td>';
echo '<td bgcolor="#CCCCCC">On divise le rendement net moyen pondéré instantané des actions du CAC40 ( <b> '.$pourcentage_action.' % </b>), par la somme du rendement net moyen pondéré instantané des action du CAC40 et le rendement instantané du livret A ( <b> '.$pourcentage_livret_A.' % ) </b></td>';
echo '</tr>'."\n";


//methode OAT

$rendement_oat=$taux_oat * (1 - $taux_cgs_rds - $taux_impot);
$repartition=round (100 * ($rendement_net / ($rendement_net + $rendement_oat)),5);
$pourcent_rendement_oat = $rendement_oat * 100;

echo '<tr>';
echo '<td bgcolor="#CCCCCC">comparaison OAT TEC 10</td>';
echo '<td bgcolor="#CCCCCC">'.$repartition.' %</td>';
echo '<td bgcolor="#CCCCCC">On divise le rendement net moyen pondéré instantané des actions du CAC40 ( <b> '.$pourcentage_action.' % </b>), par la somme du rendement net moyen pondéré instantané des action du CAC40 et le rendement net instantané des obligations assimilable au trésor de 10 ans ( <b> '.$pourcent_oat.' % ) </b></td>';
echo '</tr>'."\n";


//formule benjamin graham adapte au CAC40

#echo "valeur CAC40 : " . $valeur_CAC40 . "\n";
$prix_CAC40=get_quote('^FCHI');
$facteur_CAC40=$prix_CAC40/$valeur_CAC40;
#echo "FACTEUR CAC40 : " . $facteur_cac40 . "\n";
$valeur_intrinseque_brut_CAC40=$facteur_CAC40*$benefice_CAC40;
$valeur_intrinseque_net_CAC40=0.035*$valeur_intrinseque_brut_CAC40/$taux_oat;
$repartition=round (100 * ($valeur_intrinseque_net_CAC40 / ($valeur_intrinseque_net_CAC40 + $prix_CAC40)),5);
$valeur_intrinseque_brut_CAC40=round($valeur_intrinseque_brut_CAC40,2);
$valeur_intrinseque_net_CAC40=round($valeur_intrinseque_net_CAC40,2);


echo '<tr>';
echo '<td bgcolor="#CCCCCC">Formule benjamin graham</td>';
echo '<td bgcolor="#CCCCCC">'.$repartition.' %</td>';
echo '<td bgcolor="#CCCCCC">On divise la valeur estimée du CAC40 issu de la formule de benjamin graham ( <b> '.$valeur_intrinseque_net_CAC40.'</b>), par la somme de la valeur actuelle du CAC40 (<b> '.$prix_CAC40.'</b>) et la valeur estimée du CAC40 issu de la formule de benjamin graham </td>';
echo '</tr>'."\n";



// fin du tableau.

echo '</table>'."\n";
echo '</br>';
echo "\n";

// info supplementaire

$pourcent_cgs_rds=100 * $taux_cgs_rds;
$pourcent_impot= 100 * $taux_impot;
$pourcent_action= 100 * $rendement;


echo 'Taux actuel CGS + RDS (prélèvement sociaux) : <b>'.$pourcent_cgs_rds.' % </b>' ;
echo '</br>';
echo "\n";
echo 'Taux actuel d\'imposition (forfait libératoire) : <b>'.$pourcent_impot.' % </b>';
echo '</br>';
echo "\n";
echo 'Rendement instantané brut des actions du cac40 : <b> '.$pourcent_action.' % </b>';
echo '</br>';
echo "\n";
echo 'Rendement instantané brut des obligation 10 ans (OAT TEC 10): <b> '.$pourcent_oat.' % </b>';
echo '</br>';
echo "\n";
echo 'Valeur intrinsèque brut du CAC40 (avec un PER=12 sur 10ans) : <b> '.$valeur_intrinseque_brut_CAC40.'  </b>';
echo '</br>';
echo "\n";

?>
