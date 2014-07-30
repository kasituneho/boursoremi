<?php

//a ameliorer si PER < 0 ou PER > 100

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de sélection de la base');
$requete_permoy=mysql_query("select nom,per.titre,avg(valeur) as permoy from action,per where  per.titre=action.titre group by per.titre order by permoy asc")OR die('Erreur de la requête MySQL');
$total = mysql_num_rows($requete_permoy);

echo 'ASTUCE 1: Cliquez sur le titre de la colonne pour faire des tris croissant ou décroissant de cette colonne';
echo '<br>';
echo 'ASTUCE 2: Laisser le pointeur de souris sur le titre la colonne pour obtenir un info-bulle expliquant le contenu de cette colonne';
echo '<br>';

// si on a récupéré un résultat on l'affiche.
if($total) {
    	// debut du tableau
	echo '<table class="sortable" >'."\n";
	echo '<thead>';
        // entete du tableau
        echo '<tr>';
        echo '<th bgcolor="#669999"><b><u><span title="nom : nom officiel de l\'entreprise">Denomination</u></b></th>';
       	echo '<th bgcolor="#669999"><b><u><span title="prix de l\'action divise par le bénéfice de l\'année courante">PER0</span></u></b></th>';
       	echo '<th bgcolor="#669999"><b><u><span title="prix de l\'action divise par le bénéfice de l\'année précédente">PER1</span></u></b></th>';
	for ($i=2;$i<=10;$i++)
	{
        	echo '<th bgcolor="#669999"><b><u><span title="prix de l\'action divise par le bénéfice des '.$i.' dernieres années">PER'.$i.'</span></u></b></th>';
	}
	echo '<th bgcolor="#669999"><b><u><span title="moyenne de l\'ensemble des PER de cette action">PER moyen</span></u></b></th>' ;
     	echo '</tr>'."\n";
	echo '</thead>';
	echo '<tbody>';
	while($row_permoy = mysql_fetch_array($requete_permoy)) 
	{
		echo '<tr>';
		$nom=$row_permoy["nom"];
		$action=$row_permoy["titre"];
		$permoy=round ($row_permoy["permoy"],2);
		echo '<td bgcolor="#CCCCCC">'.$nom.'</td>';
		for ($temps=0;$temps<=10;$temps++)
		{
			$requete_per=mysql_query("select valeur from per where temps=\"$temps\" and titre=\"$action\" " )OR die('Erreur de la requête per');
			while($row_per = mysql_fetch_array($requete_per))
			{
				$per=round($row_per["valeur"],2);
				echo '<td bgcolor="#CCCCCC">'.$per.'</td>';
			}
		}
		echo '<td bgcolor="#CCCCCC">'.$permoy.'</td>';
		echo '</tr>'."\n";
    	}

	//Calcul des PER moyen par annee

	 echo '<tr>';
	echo '<td bgcolor="#CCCCCC"><b>'.'MOYENNE'.'</b></td>';
	for ($temps=1;$temps<=10;$temps++)
        {
                $requete_per=mysql_query("select avg(valeur) as moyenne from per where temps=\"$temps\" " )OR die('Erreur de la requête per');
                while($row_per = mysql_fetch_array($requete_per))
                {
                        $per=round($row_per["moyenne"],2);
                        echo '<td bgcolor="#CCCCCC"><b>'.$per.'</b></td>';
                }
        }
	$requete_per=mysql_query("select avg(z.permoy) as perfin from (select avg(valeur) as permoy from per   group by titre) as z;"); 	
	while($row_per = mysql_fetch_array($requete_per))
        {
		$per=round($row_per["perfin"],2);
		echo '<td bgcolor="#CCCCCC"><b>'.$per.'</b></td>';
        }

	echo '</tr>'."\n";
	echo '</tbody>';
    	echo '</table>'."\n";
    // fin du tableau.
}
else echo 'Pas d\'enregistrements dans cette table...';

mysql_close();
echo '</br>';
echo "\n";

?>
