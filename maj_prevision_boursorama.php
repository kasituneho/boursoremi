<?php

// Ce script sert à mettre à jour le bna,dividende,rendement et per de l annee en coursi (c est une estimation)

//Definition

$annee=date("Y")-1;
$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$temps=0;

// recuperation palmares bna

$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');
mysql_select_db($base) OR die('Erreur de sélection de la base');
$requete_action = mysql_query("select titre,boursorama from action")OR die('Erreur de la requête MySQL action');
while($row_action = mysql_fetch_array($requete_action))
{
        $action=$row_action["titre"];
	$action_boursorama=$row_action["boursorama"];
	$page_boursorama="http://www.boursorama.com/bourse/profil/resume_societe.phtml?symbole=".$action_boursorama;
	$html = file_get_contents($page_boursorama);
        #print_r($html);

	// Find and load the table

	preg_match_all("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
        #print_r($table_html);

	$bna=null;
        $num_cadre=7;

 	//selon les pages webs les infos ne sont pas dans le meme cadre .....

	while (is_null($bna))
	{

               // Iterate each row
        	preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0][$num_cadre], $matches);

        	//titre
        	preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/s", $matches[1][0], $column_header);

        	$table=array();
        	foreach($matches[1] as $row_html)
        	{
               		preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
                	$row = array();
                	for($i=0; $i<count($td_matches[1]); $i++)
                	{
                       		$td = strip_tags(html_entity_decode($td_matches[1][$i]));
                        	$row[$column_header[1][$i+1]] = $td;
               		}
                	if(count($row) > 0)
                	$table[] = $row;
        	}
        	$bna=$table[0][$annee];
		$num_cadre--;
        }

	//suite de la recuperation

	$dividende=$table[1][$annee];
	if ($dividende == "ND")
	{
		$dividende=0;
	}
	$rendement=strstr($table[2][$annee]," ",true);
	if ($rendement == "ND")
	{
		$rendement=0;
	}
	else
	{
		$rendement=$rendement/100;
	}
	$per=$table[3][$annee];

	echo "$annee \n";
	echo "$action $bna $dividende $rendement $per\n";
 

	//misen en BDD
	
	$requete_existe=mysql_query("select * from bna where titre=\"$action\" and annee=$annee" ) or die('erreur de la requete bna');
	$bna_existe=mysql_num_rows($requete_existe);
         if ( $bna_existe != 0)
         {
		$requete_bna = "update bna set valeur=$bna where titre=\"$action\" and annee=$annee ";
         }
         else
         {
		echo "UPDATE \n";
		$requete_bna = "INSERT  INTO  bna VALUES ('$annee','$action','$bna')";
         }
         $requete = mysql_query($requete_bna) or die('erreur update bna') ;

	$requete_existe=mysql_query("select * from dividende where titre=\"$action\" and annee=$annee" ) or die('erreur de la requete dividende');
	$dividende_existe=mysql_num_rows($requete_existe);
         if ( $dividende_existe != 0)
         {
		$requete_dividende = "update dividende set valeur=$dividende where titre=\"$action\" and annee=$annee ";
         }
         else
         {
		$requete_dividende = "INSERT  INTO  dividende VALUES ('$annee','$action','$dividende')";
         }
         $requete = mysql_query($requete_dividende) or die('erreur update dividende') ;
	
	$requete_existe=mysql_query("select * from rendement where titre=\"$action\" and temps=0" ) or die('erreur de la requete rendement');
	$rendement_existe=mysql_num_rows($requete_existe);
         if ( $rendement_existe != 0)
         {
		$requete_rendement = "update rendement set valeur=$rendement where titre=\"$action\" and temps=0";
         }
         else
         {
		$requete_rendement = "INSERT  INTO  rendement VALUES ('$action','$temps','$rendement')";
         }
         $requete = mysql_query($requete_rendement) or die('erreur update rendement') ;
	
	$requete_existe=mysql_query("select * from per where titre=\"$action\" and temps=0" ) or die('erreur de la requete per');
	$per_existe=mysql_num_rows($requete_existe);
         if ( $per_existe != 0)
         {
		$requete_per = "update per set valeur=$per where titre=\"$action\" and temps=0";
         }
         else
         {
		$requete_per = "INSERT  INTO  per VALUES ('$action','$temps','$per')";
         }
         $requete = mysql_query($requete_per) or die('erreur update per') ;
}

mysql_close();

?>
