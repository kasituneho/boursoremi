<?php

#$chemin_fichier = "http://www.euronext.com/trader/indicescomposition/composition-4411-EN-FR0003500008.html?selectedMep=1";
$chemin_fichier = "https://indices.nyx.com/fr/products/indices/FR0003500008-XPAR";

$html=file_get_contents($chemin_fichier);

  // Find and load the table
preg_match_all("/<table.*?>.*?<\/[\s]*table>/s", $html, $table_html);
//  echo $table_html[0][0];
//print$table_html[0][0];
// echo $table_html[1][0];
//echo $table_html[1][1];

//print_r($table_html[0][1]);
#exit ();

  // Get title for each row
  preg_match_all("/<th.*?>(.*?)<\/[\s]*th>/s", $table_html[0][0], $matches);
  #print_r($matches);
  $row_headers = $matches[1];
  #print_r($row_headers);
 
  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/tr>/s", $table_html[0][0], $matches);
  #print_r($matches[1]);
 
  $table = array();
  
  foreach($matches[1] as $row_html)
  {
    preg_match_all("/<td.*?>(.*?)<\/td>/s", $row_html, $td_matches);
    $row = array();
    #print_r($row_html);
    //print_r($td_matches[1]);
    
    for($i=0; $i<count($td_matches[1]); $i++)
    {
      $td = strip_tags(html_entity_decode($td_matches[1][$i]));
      $row[$i] = $td;
    }
 
    if(count($row) > 0)
      $table[] = $row;
  }

//print_r($table); 


$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');
mysql_select_db($base) OR die('Erreur de sélection de la base');
$requete_action = mysql_query("select titre from action where CAC40=1 order by nom asc;")OR die('Erreur de la requête MySQL titre');
$i=0;
while($row_action = mysql_fetch_array($requete_action))
{
	$action=$row_action['titre'];
	#print_r($table[$i]);
	$poids = str_replace(',', '.',($table[$i][3]))/100;
	echo "$action $poids \n";
	if ($poids > 0)
	{
		$requete_poids = "update action set poids=$poids where titre=\"$action\" ";
       		mysql_query($requete_poids) or die( mysql_error() ) ;
	}
	$i++;
}

mysql_close();
?>

