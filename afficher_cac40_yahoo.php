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

$quote = get_quote('^FCHI');
echo "\n" . "VALEUR DU CAC40 : " . $quote;
echo "\n"
?>

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
/* Titre          : Recuperer sur yahoo finance toutes les actins du cac 40   */
/*                                                                            */
/* Auteur         : Chareyron Remi                                            */
/* Date édition   : 2011/06/01                                                */
/*                                                                            */
/******************************************************************************/


// Vous désirez récupérer le source d'une page sur un autre serveur,
// voici le principe d'un aspirateur, avec ce script vous pourrez récupérer
// le contenu d'une page, si celle ci est accessible.


$chemin_fichier = "http://fr.finance.yahoo.com/q/cp?s=^FCHI";
$fp=@fopen($chemin_fichier,"r");


if($fp)
{
   while(!feof($fp))
   {
   $html .= fgets($fp,1024);
   }
   

  // Find the table
  preg_match_all("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
#  echo $table_html[0][3];


  // Get title for each row
  preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/", $table_html[0][7], $matches);
 #  print_r($matches); 
  $row_headers = $matches[1];

  
 
  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $table_html[0][7], $matches);
 
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

# Afficher le tableau

for ($i=0;$i<sizeof($table);$i++)
    {
	echo $table[$i]["Symbole"];
	echo "		";
	$espace=strpos($table[$i]["Dernier Ã©change"]," "); 
        $table[$i]["Dernier Ã©change"]=substr($table[$i]["Dernier Ã©change"],0,$espace);
	echo $table[$i]["Dernier Ã©change"];
	echo "\n";
    }



}
else
{
echo "Impossible d'ouvrir la page $chemin_fichier";
}

?>

