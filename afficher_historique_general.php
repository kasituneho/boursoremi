<?php

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de s�lection de la base');
$result=mysql_query("select titre from action")OR die('Erreur de la requ�te MySQL');
$total = mysql_num_rows($result);

// si on a r�cup�r� un r�sultat on l'affiche.
if($total) {
    // debut du tableau
    echo '<table bgcolor="#FFFFFF">'."\n";
        echo '<tr>';
        echo '<td bgcolor="#669999"><b><u>titre</u></b></td>';
        echo '<td bgcolor="#669999"><b><u>nb benef</u></b></td>';
        echo '<td bgcolor="#669999"><b><u>nb resultat</u></b></td>';
	echo '<td bgcolor="#669999"><b><u>ratio benef</u></b></td>';
        echo '<td bgcolor="#669999"><b><u>nb div</u></b></td>';
        echo '<td bgcolor="#669999"><b><u>nb distribution</u></b></td>';
        echo '<td bgcolor="#669999"><b><u>ratio div</u></b></td>';
      echo '</tr>'."\n";
    // lecture et affichage des r�sultats sur 2 colonnes, 1 r�sultat par ligne.    
	while($row = mysql_fetch_array($result)) 
	{
		$action=$row["titre"];
		$nb_resultat=mysql_query("select count(*) as nb_resultat from bna where titre=\"$action\"");
		$nb_benef=mysql_query("select count(*) as nb_benef from bna where titre=\"$action\" and valeur!=0");
		$ratio_benef=mysql_query("select (1 - (select count(*) from bna where valeur=0 and titre=\"$action\") / count(*)) * 100 as benefice from bna where titre=\"$action\" ");
		$nb_distribution=mysql_query("select count(*) as nb_distribution from dividende where titre=\"$action\" ");
		$nb_div=mysql_query("select count(*) as nb_div from dividende where titre=\"$action\" and valeur!=0 ");
		$ratio_div=mysql_query("select (1 - (select count(*) from dividende where valeur=0 and titre=\"$action\") / count(*)) * 100 as dividende from dividende where titre=\"$action\" ");


		echo '<tr>';
        	echo '<td bgcolor="#CCCCCC">'.$row["titre"].'</td>';
		
		while($row_nb_benef = mysql_fetch_array($nb_benef))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_nb_benef["nb_benef"].'</td>';
		}
		while($row_nb_resultat = mysql_fetch_array($nb_resultat))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_nb_resultat["nb_resultat"].'</td>';
		}
		while($row_benef = mysql_fetch_array($ratio_benef))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_benef["benefice"].'</td>';
		}
		while($row_nb_div = mysql_fetch_array($nb_div))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_nb_div["nb_div"].'</td>';
		}
		while($row_nb_distribution = mysql_fetch_array($nb_distribution))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_nb_distribution["nb_distribution"].'</td>';
		}
		while($row_div = mysql_fetch_array($ratio_div))
		{
			echo '<td bgcolor="#CCCCCC">'.$row_div["dividende"].'</td>';
		}
      		echo '</tr>'."\n";
    	}
    	echo '</table>'."\n";
    // fin du tableau.
}
else echo 'Pas d\'enregistrements dans cette table...';

mysql_close();
echo '</br>';
echo "\n";

?>
