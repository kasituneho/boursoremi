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

//definitions

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$annee=date("Y");
$annee_debut=$annee -10;

//liste des requetes


//main

$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');     
mysql_select_db($base) OR die('Erreur de sélection de la base'); 	
$requete_action = mysql_query("select distinct titre  from action where active=1 order by titre asc")OR die('Erreur de la requête MySQL action'); 
while($row_titre = mysql_fetch_array($requete_action))
{
	$action=$row_titre["titre"];
	$prix=get_quote($action);
	$BNA=0;
	$nb_BNA=0;
	$requete_BNA = mysql_query("select BNA  from valeur where titre=\"$action\"")
	OR die('Erreur de la requête MySQL BNA');	
	while($row_bna = mysql_fetch_array($requete_BNA))	
	{
		$BNA+=$row_bna["BNA"];	
		$nb_BNA++;
	}
	$dividende=0;
	$nb_dividende=0;
	$requete_dividende = mysql_query("select dividende  from valeur  where titre=\"$action\" ")
	OR die('Erreur de la requête MySQL dividende');	
	while($row_dividende = mysql_fetch_array($requete_dividende))	
	{
		$dividende+=$row_dividende["dividende"];	
		$nb_dividende++;
	}
	$BNA_valeur=$BNA/$nb_BNA;
	$dividende_valeur=$dividende/$nb_dividende;
	$total_valeur=($BNA_valeur + $dividende_valeur)/2;
	//$total_valeur=round($total_valeur,"3");
	$PER=0;
	$TDB=0;
	$ANC=0;	
	$solvabilite=0;
	$croissance=0;
	$investisseur="NDEF";
	$gain=0;
	$rendement=0;
	if ( $prix > 0 )
	{
		// Plus value esperee

		$gain=round((($total_valeur - $prix) / $prix),2);

		// Rendement

	 	$requete_rendement = mysql_query("select avg(valeur)  from dividende  where titre=\"$action\" and annee < \"$annee\" and annee >= \"$annee_debut\" ")	;
	 	while($row_rendement = mysql_fetch_array($requete_rendement))	
		{
			$rendement= $row_rendement["avg(valeur)"] / $prix;
		}

		//PER

		$requete_per = mysql_query("select avg(valeur)  from bna  where titre=\"$action\" and annee < \"$annee\" and annee >= \"$annee_debut\" ")    ;
                while($row_per = mysql_fetch_array($requete_per))
                {
                        $PER= $prix / $row_per["avg(valeur)"] ;
                }
		// ANC
                //$annee_precedente=$annee;
                $annee_precedente=$annee -1;
                $requete_anc = mysql_query("select valeur  from anc  where titre=\"$action\" and annee = \"$annee_precedente\"  ")    ;
                while($row_anc = mysql_fetch_array($requete_anc))
                {
                        $ANC= $prix / $row_anc["valeur"] ;
			$ANC= round($ANC,4);
                }
		

	

	// taux de distribution

		$requete_tdb_dividende = mysql_query("select sum(valeur)  from dividende  where titre=\"$action\" and annee < \"$annee\" and annee >= \"$annee_debut\" ")    ;
		while($row_tdb_dividende = mysql_fetch_array($requete_tdb_dividende))
        	{	
			$requete_tdb_bna = mysql_query("select sum(valeur)  from bna  where titre=\"$action\" and annee < \"$annee\" and annee >= \"$annee_debut\" ")    ;
               		while($row_tdb_bna = mysql_fetch_array($requete_tdb_bna))
               		{
                       		$TDB= $row_tdb_dividende["sum(valeur)"] / $row_tdb_bna["sum(valeur)"] ;
				$TDB = round ($TDB,4);
               		}
        	}

	//endettement

        $annee_precedente=$annee -1;
        //$annee_precedente=$annee;
        $requete_gearing = mysql_query("select valeur  from gearing  where titre=\"$action\" and annee = \"$annee_precedente\"  ")    ;
        while($row_gearing = mysql_fetch_array($requete_gearing))
        {
                $solvabilite=$row_gearing["valeur"] ;
        }

		//croissance

		$debut_interval=$annee_debut + 2;
		$fin_interval=$annee - 2;		
		$requete_bna_debut = mysql_query("select avg(valeur)  from bna  where titre=\"$action\" and annee < \"$debut_interval\" and annee >= \"$annee_debut\" ")   ; 
		while($row_bna_debut = mysql_fetch_array($requete_bna_debut))
		{	
			$bna_debut= $row_bna_debut["avg(valeur)"] ;
			$requete_bna_fin = mysql_query("select avg(valeur)  from bna  where titre=\"$action\" and annee < \"$annee\" and annee >= \"$fin_interval\" ")   ;
		 	while($row_bna_fin = mysql_fetch_array($requete_bna_fin))
		 	{
				$bna_fin= $row_bna_fin["avg(valeur)"] ;
				if ($bna_debut != 0)
				{
					$croissance = ($bna_fin - $bna_debut) / $bna_debut ;
				}
				else
				{
					$croissance = 0;
				}	
			}
		}	
	

		// determiner le type d'investisseur

		$requete_benefice=mysql_query("select sum(valeur) from bna where titre=\"$action\" ") ;
		while($row_benefice = mysql_fetch_array($requete_benefice))
		if ($row_benefice["sum(valeur)"] < 0)
		{
			$investisseur="speculateur";
		}
		else 
		{
			$requete_nucleaire=mysql_query("select secteur from action where titre=\"$action\" ") ;	
			while($row_nucleaire = mysql_fetch_array($requete_nucleaire))
		 	if ($row_nucleaire["secteur"] == "nucleaire")
                       	{
				$investisseur="speculateur";
                       	}
			else
			{
				$requete_min_benefice=mysql_query("select min(valeur) from bna where titre=\"$action\" ") ;	
			 	while($row_min_benefice = mysql_fetch_array($requete_min_benefice))
				if ($row_min_benefice["min(valeur)"] < 0)
                		{
                       			$investisseur="entreprenant";
				}	
				else
				{
					$requete_min_dividende=mysql_query("select min(valeur) from dividende where titre=\"$action\" ") ;
                                	while($row_min_dividende = mysql_fetch_array($requete_min_dividende))
					if ($row_min_benefice["min(valeur)"] <= 0)
                                	{
                                        	$investisseur="entreprenant";
                                	}
                               		else
					{
						if ( $TDB < 0.33333 )
						{
							$investisseur="entreprenant";
						}	
						else
						{
							if ($PER > 25)
							{	
								 $investisseur="entreprenant";
							}
							else
							{
								if ($solvabilite > 1)
								{
									$investisseur="entreprenant";
								}
								else
								{
									$investisseur="defensif";
								}
							}	

						}
					}
                		}		
			}
         	}
	}
	// inserer dans la BDD
	$requete_existe=mysql_query("select * from palmares where titre=\"$action\" ");
	$palmares_existe=mysql_num_rows($requete_existe);
	if ($palmares_existe != 0)
	{

  		$requete_palmares = "update palmares
		SET prix=$prix , valeur=$total_valeur,PER=$PER, rendement=$rendement, TDB=$TDB,ANC=$ANC, solvabilite=$solvabilite
		,croissance=$croissance,investisseur=\"$investisseur\",gain=$gain where titre=\"$action\" ";
	}
	else
	{

 		$requete_palmares = "INSERT  INTO palmares
        	VALUES ('$action', '$prix' , '$total_valeur', '$PER', '$rendement' ,'$TDB','$ANC','$solvabilite','$croissance','$investisseur','$gain') " ;
		
	}
	echo "$action $prix $total_valeur $PER $rendement $TDB $ANC $solvabilite $croissance $investisseur $gain \n";
	mysql_query($requete_palmares) OR die('Erreur de la requête MySQL palmares');
}

mysql_close();
?> 
