<?php

$connexion = mysql_connect('localhost', 'bourse', 'Bgr@h@m') OR die('Erreur de connexion');
mysql_select_db('bourse') OR die('Erreur de s�lection de la base');

//choisir un classment en fonctionnant du type d investisseur

switch ($argv[1])
{
	case "defensif" :
	$result=mysql_query("select action.nom,palmares.* from action,palmares where investisseur='defensif'
        and action.titre=palmares.titre  order by gain desc")OR die('Erreur de la requ�te MySQL');

	//affichage des regles

	echo '<h3>REGLES DEFENSIVES </h3><br>';

	echo '-  <a href="http://fr.wikipedia.org/wiki/B%C3%A9n%C3%A9fice_par_action"> B�n�fice </a>et versement de <a href="http://fr.wikipedia.org/wiki/Dividende">dividendes</a> : ininterrompu sur 10 ans<br>';

	echo '- <a href="http://en.wikipedia.org/wiki/Dividend_payout_ratio">Taux De DistriBution</a> sur 10 ans > 1/3<br>';
	
	echo '- <a href="http://fr.wikipedia.org/wiki/PER_%28%C3%A9conomie%29">Price Earning Ratio</a> sur 10 ans  < 25<br>';

	echo '- <a href="http://en.wikipedia.org/wiki/Dividend_yield">Rendement</a> annuel sur 10 ans > 2%<br>';

	echo '- Prix  action < 250% <a href="http://fr.wikipedia.org/wiki/Actif_net_comptable">Actif Net Comptable </a> de l.ann�e pr�c�dente<br>';

	echo '- Dette sur <a href="http://fr.wikipedia.org/wiki/Capitaux_propres">fond propre</a> de l\'ann�e pr�c�dente < 100%<br>';

	echo '- la societe ne doit pas avoir un risque avec un cout immense (comme le nucleaire) comme le montre <a href="http://fr.wikipedia.org/wiki/Beno%C3%AEt_Mandelbrot">Benoit Mandelbrot</a> dans son livre Une approche fractale des march�s financiers';
	echo '<br>';
	echo '<br>';

	break;
	
	case "entreprenant" :
	$result=mysql_query("select action.nom,palmares.* from action,palmares
        where investisseur!='speculateur' and action.titre=palmares.titre  order by gain desc")OR die('Erreur de la requ�te MySQL');

	//AFFICHAGE DES REGLES ENTREPRENANT

	echo '- la societe ne doit pas avoir un risque avec un cout immense (comme le nucleaire) comme le montre <a href="http://fr.wikipedia.org/wiki/Beno%C3%AEt_Mandelbrot">Benoit Mandelbrot</a> dans son livre Une approche fractale des march� financiers<br>';
	echo 'La r�gle : l\'entreprise doit faire des b�n�fice cumul� > 0  sur 10 ans<br><br>';

	break;
	
	case "speculateur" :
	$result=mysql_query("select action.nom,palmares.* from action,palmares where action.titre=palmares.titre
        order by gain desc")OR die('Erreur de la requ�te MySQL');
	break;
	
	default :
	exit ("\nusage : afficher_palmares.php PROFIL_INVESTISSEUR
	\nPROFIL_INVESTISSEUR : defensif , entreprenant ou speculateur
	\n");
	break;
	
}

echo 'ASTUCE 1: Cliquez sur le titre de la colonne pour faire des tris croissant ou d�croissant de cette colonne';
echo '<br>';
echo 'ASTUCE 2: Laisser le pointeur de souris sur le titre la colonne pour obtenir un info-bulle expliquant le contenu de cette colonne';
echo '<br>';

// debut du tableau

echo '<table class="sortable">'."\n";
echo '<thead>';
echo '<tr>';
echo '<th bgcolor="#669999"><b><u><span title="nom : nom officiel de l\'entreprise" >nom</span></u></b></th>';
echo '<th bgcolor="#669999"><b><u><span title="titre :  identifiant de l\'action sur yahoo finance,le code avant le . definit la soci�t� (Ex: ACA = cr�dit agricole SA) , le code apres le . d�finit le lieu de quotation (Ex : PA = PARIS)">titre </span></u></b></th>';
echo '<th bgcolor="#669999"><b><u><span title= "prix : prix utilis� au moment de l\'analyse de l\'action">prix</span></u></b></th>';
echo '<th bgcolor="#669999"><b><u><span title="valeur : Estimation de la valeur intrins�que de l\'action en fonction de ses b�n�fices et dividendes">valeur</span></u></b></th>';
echo '<th bgcolor="#669999"><b><u><span title="Price Earning Ratio : Le ratio cours / b�n�fices, en anglais le Price Earning Ratio abr�g� � PER � ou � P/E �, est une expression boursi�re signifiant � coefficient de capitalisation des b�n�fices �. C\'est le rapport entre le cours de bourse d\'une entreprise et son b�n�fice moyen sur 10 ans apr�s imp�ts, ramen� � une action (b�n�fice par action).">PER</span></u></b></th>';
echo '<th bgcolor="#669999"><b><u><span title="Moyenne des 10 derniers dividende divise par le prix de l\'action">rend</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title="Taux De Distribution : Moyenne sur 10 ans de la part des b�n�fice que l\'entreprise verse en dividende a l\'actionnaire">TDB</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title="Price to Book : ratio (capitalisation boursi�re / fond propre) ou autre fa�on de calculer ce ratio  (prix / actif net comptable)">P/B</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title="Dette sur Fond Propre : dette brut de l\'entreprise divise par la valeur comptable des capitaux propres">D/FP</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title="calcul de la croissance : augmentation moyenne sur 3 ans des benefice sur 10 ans"> croissance</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title= "invest : Type d\'investisseur d�finit par Benjamin Graham, DEFENSIF : personne ne souhaitant s\'occuper de son portefeuille  qu\'une fois par an, ENTREPRENANT : investisseur pouvant s\'occuper de son portefeuille chaque semaine, SPECULATEUR : joueur de casino" >invest</span></u></b></th>' ;
echo '<th bgcolor="#669999"><b><u><span title="Esperance math�matiques de la plus-value de l\'action">gain</span></u></b></th>' ;
echo '</tr>'."\n";
echo '</thead>';
echo '<tbody>';

// affichage des resultat

while($row = mysql_fetch_array($result)) 
{
	echo '<tr>';
	echo '<td bgcolor="#CCCCCC">'.$row["nom"].'</td>';
	echo '<td bgcolor="#CCCCCC">'.$row["titre"].'</td>';
	echo '<td bgcolor="#CCCCCC">'.$row["prix"].'</td>';
	echo '<td bgcolor="#CCCCCC">'.$row["valeur"].'</td>';
	echo '<td bgcolor="#CCCCCC">'.$row["PER"].'</td>';
	$rendement_pourcent= $row["rendement"] * 100;
	echo '<td bgcolor="#CCCCCC">'.$rendement_pourcent."%".'</td>';
	$TDB_pourcent= $row["TDB"] * 100;
	echo '<td bgcolor="#CCCCCC">'.$TDB_pourcent."%".'</td>';
	$ANC_pourcent=$row["ANC"] * 100;
	echo '<td bgcolor="#CCCCCC">'.$ANC_pourcent."%".'</td>';
	$solvabilite_pourcent=$row["solvabilite"] * 100;
	echo '<td bgcolor="#CCCCCC">'.$solvabilite_pourcent."%".'</td>';
	$croissance_pourcent=$row["croissance"] * 100;	
	echo '<td bgcolor="#CCCCCC">'.$croissance_pourcent."%".'</td>';
	echo '<td bgcolor="#CCCCCC">'.$row["investisseur"].'</td>';
	$gain_pourcent=$row["gain"] * 100;	
	echo '<td bgcolor="#CCCCCC">'.$gain_pourcent."%".'</td>';
	echo '</tr>'."\n";
}

// fin du tableau.

echo '</table>'."\n";
echo '</tbody>';
echo '</br>';
echo "\n";
mysql_close();
?>
