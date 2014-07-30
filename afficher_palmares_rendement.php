<?php

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de sélection de la base');
$requete_rendementmoy=mysql_query("select nom,rendement.titre,avg(valeur) as rendementmoy from action,rendement where rendement.valeur > 0 and rendement.titre=action.titre group by rendement.titre order by rendementmoy desc")OR die('Erreur de la requête MySQL');
$total = mysql_num_rows($requete_rendementmoy);

echo 'ASTUCE 1: Cliquez sur le titre de la colonne pour faire des tris croissant ou décroissant de cette colonne';
echo '<br>';
echo 'ASTUCE 2: Laisser le pointeur de souris sur le titre la colonne pour obtenir un info-bulle expliquant le contenu de cette colonne';
echo '<br>';

// si on a récupéré un résultat on l'affiche.
if($total) {
    // debut du tableau
    echo '<table class="sortable">'."\n";
        // entete du tableau
	echo '<thead>';
        echo '<tr>';
        echo '<th bgcolor="#669999"><b><u><span title="nom : nom officiel de l\'entreprise">Denomination</span></u></b></th>';
        echo '<th bgcolor="#669999"><b><u><span title="dividende de l\'année courante divisé par le prix courant de l\'action">RD0</span></u></b></th>';
        echo '<th bgcolor="#669999"><b><u><span title="dividende de l\'année précédente divisé par le prix courant de l\'action">RD1</span></u></b></th>';
	for ($i=2;$i<=10;$i++)
        {
        	echo '<th bgcolor="#669999"><b><u><span title="dividende des'.$i.' années précédentes divisé par le prix courant de l\'action">RD'.$i.'</span></u></b></th>';
	}
	echo '<th bgcolor="#669999"><b><u><span title="Moyenne de tous les rendement de l\'action de ce tableau">RD moyen</u></b></th>' ;
        echo '</tr>'."\n";
	echo '</thead>';
	echo '<tbody>';
	while($row_rendementmoy = mysql_fetch_array($requete_rendementmoy)) 
	{
		echo '<tr>';
		$nom=$row_rendementmoy["nom"];
		$action=$row_rendementmoy["titre"];
		$rendementmoy=round ($row_rendementmoy["rendementmoy"]*100,2);
		echo '<td bgcolor="#CCCCCC">'.$nom.'</td>';
		for ($temps=0;$temps<=10;$temps++)
		{
			$requete_rendement=mysql_query("select valeur from rendement where temps=\"$temps\" and titre=\"$action\" " )OR die('Erreur de la requête rendement');
			while($row_rendement = mysql_fetch_array($requete_rendement))
			{
				$rendement=round($row_rendement["valeur"]*100,2);
				echo '<td bgcolor="#CCCCCC">'.$rendement.'%</td>';
			}
		}
		echo '<td bgcolor="#CCCCCC">'.$rendementmoy.'%</td>';
		echo '</tr>'."\n";
    	}

	//Calcul des rendement moyen par annee

	 echo '<tr>';
	echo '<td bgcolor="#CCCCCC"><b>'.'MOYENNE'.'</b></td>';
	for ($temps=1;$temps<=10;$temps++)
	{
		$requete_rendement=mysql_query("select avg(valeur) as moyenne from rendement where temps=\"$temps\" and valeur > 0" )OR die('Erreur de la requête rendement');
		while($row_rendement = mysql_fetch_array($requete_rendement))
		{
			$rendement=round($row_rendement["moyenne"]*100,2);
			echo '<td bgcolor="#CCCCCC"><b>'.$rendement.'%</b></td>';
		}
	}
	$requete_rendement=mysql_query("select avg(z.rendementmoy) as rendementfin from (select avg(valeur) as rendementmoy from rendement where valeur > 0 group by titre) as z;"); 	
	while($row_rendement = mysql_fetch_array($requete_rendement))
        {
		$rendement=round($row_rendement["rendementfin"]*100,2);
		echo '<td bgcolor="#CCCCCC"><b>'.$rendement.'%</b></td>';
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
