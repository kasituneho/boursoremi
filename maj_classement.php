<?php 

//definitions

$server="localhost";
$user="bourse";
$password="Bgr@h@m";
$base="bourse";
$temps=date("Y-m-d");

//liste des requetes


//main

$connexion = mysql_connect($server, $user , $password) OR die('Erreur de connexion');     
mysql_select_db($base) OR die('Erreur de sélection de la base'); 	
$requete_palmares = mysql_query("select titre from palmares order by gain desc")OR die('Erreur de la requête MySQL palmares'); 
$classement=1;
while($row_palmares = mysql_fetch_array($requete_palmares))
{
 $action=$row_palmares["titre"];
 echo "$temps $action $classement \n";
 $requete_existe=mysql_query("select * from classement where titre=\"$action\" and jour=\"$temps\" ");
 $classement_existe=mysql_num_rows($requete_existe);
 if ( $classement_existe != 0)
                {
                        $requete_classement = "update classement set classement=$classement  where titre=\"$action\" and jour=\"$temps\" ";
                }
                else
                {
                        $requete_classement = "INSERT  INTO  classement (jour,titre,classement)
                        VALUES ('$temps','$action','$classement')";
                }
                $requete = mysql_query($requete_classement) or die( mysql_error() ) ;
 $classement++;
}
mysql_close();
?> 
