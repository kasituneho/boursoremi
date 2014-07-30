<?php
/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Titre          : Aspirer une page sur le web                               */
/*                                                                            */
/* URL            : http://www.phpsources.org/scripts126-PHP.htm              */
/* Auteur         : Celina                                                    */
/* Date édition   : 24 Mai 2005                                               */
/*                                                                            */
/******************************************************************************/


// Vous désirez récupérer le source d'une page sur un autre serveur,
// voici le principe d'un aspirateur, avec ce script vous pourrez récupérer
// le contenu d'une page, si celle ci est accessible.


$chemin_fichier = "http://www.boursorama.com/tableaux/cours_az.phtml?MARCHE=1rPCAC";
$fp=@fopen($chemin_fichier,"r");


if($fp)
{
   while(!feof($fp))
   {
   $html .= fgets($fp,1024);
   }
   

  // Find the table
  preg_match_all("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
#  echo $table_html[0][1];


  // Get title for each row
  $row_headers =  array ("fleche","titre","delai","cour","variation","ouverture","haut","bas","1janvier","volume");

  
 
  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0][1], $matches);
 
  $table = array();
  
  foreach($matches[1] as $row_html)
  {
    preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
    $row = array();
    for($i=0; $i<count($td_matches[1]); $i++)
    {
      $td = strip_tags(html_entity_decode($td_matches[1][$i]));
      $row[$row_headers[$i]] = $td;
    }
 
    if(count($row) > 0)
      $table[] = $row;
	}

 #  print_r($table); 

# Afficher le tableau

for ($i=1;$i<sizeof($table);$i++)
    {
	echo $table[$i]["variation"];
	echo "		";
	echo $table[$i]["cour"];
	echo "		";
	echo $table[$i]["titre"];
	echo "				";
	echo "\n";
    }



}
else
{
echo "Impossible d'ouvrir la page $chemin_fichier";
}

?>

