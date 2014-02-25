<?php
// Informations sur la base de données MySQL
try
{
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=trombinoscope', 'root', '');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

// Transformation de la date au format US vers le format JJMMAAAA
function dateFR($date)
{
	$explode = explode("-", $date);
	$annee = intval($explode[0]);
	$mois = intval($explode[1]);
	$jour = intval($explode[2]);
	
	$LibelleMois = array("","Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");
	
	return $jour." ".$LibelleMois[$mois]." ".$annee;
}

// Transformation de la date au format FR vers le format AAAAMMJJ pour la base de données
function dateUS($date)
{
	$explode = explode("-", $date);
	$annee = intval($explode[2]);
	$mois = intval($explode[1]);
	$jour = intval($explode[0]);
	
	return $annee."-".$mois."-".$jour;
}?>