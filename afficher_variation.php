<?php
# Declaration des donnes

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$TABLE = array(array());
$MOYENNE=array();


switch ($argv[1])
{
	# Pour l instant  pour simplifier le script on approxime les mois  a 30 jours et les annees a 365 jours

	case "journaliere" :
	$temps =  array (1,2,3,4,5,6,7,8,9);
	$duree_singulier = "jour";
	$duree_pluriel = "jours";
	$duree = 1;
	break;

	case "hebdomadaire" :
	$temps =  array (7,14,21,28,35,42,49,56,63);
	$duree_singulier = "semaine";
        $duree_pluriel = "semaines";
        $duree = 7;
	break;

	case "mensuelle" :
	$temps =  array (90,120,150,180,210,240,270,300,330);
	$duree_singulier = "mois";
        $duree_pluriel = "mois";
        $duree = 30;
	break;

	case "annuelle" :
	#$temps =  array (365,730,1095,1460,1825,2190,2555,2920,3285);
	$temps =  array (365);
	$duree_singulier = "année";
        $duree_pluriel = "années";
        $duree = 365;
	break;

	default :
	exit ("\nusage : afficher_variation.php journaliere|hebdomadaire|mensuelle|annuelle
        \nexemple : afficher_variation.php hebdomadaire
        \n");
        break;
}
$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');
mysql_select_db($base) OR die('Erreur de sélection de la base');

$j=0;
foreach($temps as $NB_JOUR)
{
	$i=0;

	
	# Pour l instant  pour simplifier le script on approxime les mois  a 30 jours et les annees a 365 jours pour la requte SQL

	$requete_variation = mysql_query("select ja.titre as titre,(jj.classement - ja.classement) as variation  from classement as jj, classement as ja where  TO_DAYS(CURDATE()) - TO_DAYS(jj.jour) = \"$NB_JOUR\" and TO_DAYS(CURDATE()) - TO_DAYS(ja.jour) = 0 and ja.titre=jj.titre order by variation desc;")OR die('Erreur de la requête MySQL titre');
	while($row_variation = mysql_fetch_array($requete_variation))
	{
		$TABLE[$i][$j] = $row_variation["titre"];
		$TABLE[$i][$j+1] = $row_variation["variation"];
		if (empty ($MOYENNE[$row_variation["titre"]]))
		{
			$MOYENNE[$row_variation["titre"]]=0;
		}
		$MOYENNE[$row_variation["titre"]]+=$row_variation["variation"];
		$i++;
	}
	$j++;
	$j++;
}

$nb_colonne=sizeof($temps);

for ($k=0;$k<$i;$k++)
{
	$max_action="";
	$max_valeur=-100000;
	foreach ($MOYENNE as $action => $valeur)
	{
		if ( $valeur > $max_valeur)
		{
			$max_valeur=$valeur;
			$max_action=$action;
		}
	}
	$TABLE[$k][$j]=$max_action;
	$TABLE[$k][$j+1]=round($max_valeur/$nb_colonne,2);
	unset ($MOYENNE["$max_action"]);

}

# calcul de la moyenne


mysql_close();
$nb_ligne=$i;
$nb_colonne=$j+2;
  
echo "Augmentation ou diminution, dans le palmares général comparé avec les palmares des " . "$duree_pluriel". " précédentes</br>";
echo '<table bgcolor="#FFFFFF">'."\n";
echo '<tr>'."\n";
$singulier=1;
foreach ($temps as $valeur)
{
	$nombre=$valeur/$duree;
	if ($singulier == 0)
	{
		echo '<td colspan="2">'."$nombre $duree_pluriel".'</td>'."\n";
	}
	else
	{
		echo '<td colspan="2">'."$nombre $duree_singulier".'</td>'."\n";
		$singulier=0;
	}
}
echo '<td colspan="2">MOYENNE</td>';
echo '</tr>'."\n";
for ($i=0;$i<$nb_ligne;$i++)
{
	echo '<tr>'."\n";
	for($j=0;$j<$nb_colonne;$j++)
	{
		 echo '<td bgcolor="#CCCCCC">'.$TABLE[$i][$j].'</td>'."\n";	
	}
	 echo '</tr>'."\n";
}
echo '</table>'."\n";

?>

