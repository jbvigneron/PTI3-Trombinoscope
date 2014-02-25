<?php
// ne pas afficher les erreurs de notice
error_reporting(E_ALL ^ E_NOTICE);

// inclure les informations de la base de données MySQL
include("../sql.php");

// Infos pour la navigation entre les pages
$page = $_GET['page'];

// Si la page n'est pas spécifiée dans le GET, la page sera l'accueil
if(empty($page)) $page = 'accueil';

// On vérifie si la page existe
if(file_exists($page.'.php') == false)
{	?>
<script type="text/javascript">
document.location.href="../index.php"
</script>
<?php
}
else
{
	// Afficher la page demandée
	include($page.'.php');
}
?>