<?php

$annee=date("Y");
$annee_debut=$annee -13;

//requete BDD pour cherche les 10 dernieres annee de benefice net par action

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de sélection de la base');
$requete_bna=mysql_query("select action.nom,bna.titre,bna.annee,bna.valeur from action,bna where annee >= \"$annee_debut\" and annee < \"$annee\" and action.titre=bna.titre and action.active=1 order by action.nom,annee asc;")OR die('Erreur de la requête MySQL');

// creation du html

echo '<table bgcolor="#FFFFFF">'."\n";

// Entete du tableau

echo '<tr>';
echo '<td bgcolor="#669999"><b><u>denomination</u></b></td>';
$annee_tableau=$annee_debut;
while ($annee_tableau < $annee)
{
        echo '<td bgcolor="#669999"><b><u>'.$annee_tableau.'</u></b></td>';
	$annee_tableau++;
}
echo '</tr>'."\n";

//Contenu du tableau

while($row = mysql_fetch_array($requete_bna)) 
{
	if ($row["annee"] == $annee_debut)
	{	
		echo '<tr>';
		echo '<td bgcolor="#CCCCCC">'.$row["nom"].'</td>';
	}	
	$BNA=round($row["valeur"],3);
	echo '<td bgcolor="#CCCCCC">'.$BNA.'</td>';
	if ($row["annee"] == ($annee -1))
        {
		echo '</tr>'."\n";
	}
    }
    echo '</table>'."\n";

// fin du tableau.
echo '</br>';
echo "\n";

//fin creation html

mysql_close();
?>
