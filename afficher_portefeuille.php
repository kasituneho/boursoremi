<?php

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de sélection de la base');

//choisir un classment en fonctionnant du type d investisseur

switch ($argv[1])
{
        case "defensif" :
        $portefeuille=mysql_query("select action.nom,palmares.titre,palmares.gain,action.secteur from action,palmares where investisseur='defensif'
        and action.titre=palmares.titre and gain>0 order by gain desc")OR die('Erreur de la requête MySQL');
        break;

        case "entreprenant" :
        $portefeuille=mysql_query("select action.nom,palmares.titre,palmares.gain,action.secteur from action,palmares
        where investisseur!='speculateur' and action.titre=palmares.titre and gain>0 order by gain desc")OR die('Erreur de la requête MySQL');

        break;

        case "speculateur" :
        $portefeuille=mysql_query("select action.nom,palmares.titre,palmares.gain,action.secteur from action,palmares where action.titre=palmares.titre
        and gain>0 order by gain desc")OR die('Erreur de la requête MySQL');
        break;

        default :
        exit ("\nusage : afficher_portefeuille.php PROFIL_INVESTISSEUR
        \nPROFIL_INVESTISSEUR : defensif , entreprenant ou speculateur
        \n");
        break;

}

$tableau=array(array());
$num_valeur=0;
$nb_valeur=0;
$liste_secteur=array();
$total=array();
$action=array();
$identifiant=array();

while($row = mysql_fetch_array($portefeuille))
{
	$action[$num_valeur]=$row["nom"];
	$identifiant[$num_valeur]=$row["titre"];
	$tableau[$num_valeur][0]=$row["gain"];
	$secteur_existe=0;	
	foreach ($liste_secteur as $secteur)
	{
		if ( $row["secteur"] == $secteur)
		{
			$secteur_existe=1;
		}
	}
	if ($secteur_existe == 1)
	{
		$tableau[$num_valeur][1]=0;
		$tableau[$num_valeur][2]=0;
		$tableau[$num_valeur][3]=0;
		$tableau[$num_valeur][4]=0;
		$tableau[$num_valeur][5]=0;
		$tableau[$num_valeur][6]=0;
		$tableau[$num_valeur][7]=0;
       	}
	else
	{
		$liste_secteur[]=$row["secteur"];
		if ($nb_valeur < 3)
		{
			$tableau[$num_valeur][1]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][1]=0;
		}
		if ($nb_valeur < 4)
		{
			$tableau[$num_valeur][2]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][2]=0;
		}
		if ($nb_valeur < 5)
		{
			$tableau[$num_valeur][3]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][3]=0;
		}
		if ($nb_valeur < 6)
		{
			$tableau[$num_valeur][4]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][4]=0;
		}
		if ($nb_valeur < 8)
		{
			$tableau[$num_valeur][5]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][5]=0;
		}
		if ($nb_valeur < 10)
		{
			$tableau[$num_valeur][6]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][6]=0;
		}
		if ($nb_valeur < 12)
		{
			$tableau[$num_valeur][7]=$row["gain"];			
		}
		else
		{
			$tableau[$num_valeur][7]=0;
		}
		$nb_valeur++;
	}
	$num_valeur++;
}


for ($j=0;$j<8;$j++)
{
	$total[$j]=0;
	for ($i=0;$i<$num_valeur;$i++)
	{
		$total[$j]=$total[$j]+$tableau[$i][$j];		
	}
}

for ($j=0;$j<8;$j++)
{
	for ($i=0;$i<$num_valeur;$i++)
        {
		$tableau[$i][$j]=100 * $tableau[$i][$j] / $total[$j];
        }
}

//Affichage du tableau

echo '<table bgcolor="#FFFFFF">'."\n";
echo '<tr>';
echo '<td bgcolor="#669999"><b><u><span title="nom : nom officiel de l\'entreprise" >nom</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title="titre :  identifiant de l\'action sur yahoo finance,le code avant le . definit la société (Ex: ACA = crédit agricole SA) , le code apres le . définit le lieu de quotation (Ex : PA = PARIS)">titre </span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 10 000 euros : 3 titres de secteurs differents">10 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 20 000 euros : 4 titres de secteurs differents">20 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 30 000 euros : 5 titres de secteurs differents">30 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 50 000 euros : 6 titres de secteurs differents">50 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 75 000 euros : 8 titres de secteurs differents">75 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 100 000 euros : 10 titres de secteurs differents">100 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "portefeuille 150 000 euros : 12 titres de secteurs differents">150 Ke</span></u></b></td>';
echo '<td bgcolor="#669999"><b><u><span title= "sans correction">superieur a 150 Ke</span></u></b></td>';

for ($i=0;$i<$num_valeur;$i++)
{
	echo '<tr align="center">';
       	echo '<td bgcolor="#CCCCCC">'.$action[$i].'</td>';
       	echo '<td bgcolor="#CCCCCC">'.$identifiant[$i].'</td>';
	echo "\n";
	for ($j=1;$j<8;$j++)
	{
        	$pourcent=round($tableau[$i][$j],3);
		if ($pourcent == 0)
		{
			echo '<td bgcolor="#CCCCCC">-</td>';	
		}
		else
		{
        		echo '<td bgcolor="#CCCCCC">'.$pourcent."%".'</td>';
		}
	}
	$pourcent=round($tableau[$i][0],3);
	echo '<td bgcolor="#CCCCCC">'.$pourcent."%".'</td>';
        echo '</tr>'."\n";
}

echo '</table>'."\n";

mysql_close();
?>
