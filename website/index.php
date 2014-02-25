<?php
// PTI3 SGBD: Trombinoscope
// Langage: PHP 5
// BDD: MySQL

// enlever les erreurs de notice
error_reporting(E_ALL ^ E_NOTICE);

// inclure les informations de la base de donn�es MySQL
include("sql.php");

// Infos pour la navigation entre les pages
$page = $_GET['page'];

// Si la page n'est pas sp�cifi�e dans le GET, la page sera l'accueil
if(empty($page)) $page = 'accueil';

// On v�rifie si la page existe
if(!file_exists($page.'.php')) $page = 'erreur';

// Afficher la page demand�e
include($page.'.php');
?>