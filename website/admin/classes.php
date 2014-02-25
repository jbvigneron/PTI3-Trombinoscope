<?php
// Remonter une classe
if($_GET['action'] == 'haut')
{
	$req = $bdd->prepare("UPDATE `CLASSE`
						SET `POSITION` = :nouvelle
						WHERE POSITION = :ancienne") or die(print_r($bdd->errorInfo()));
				
	$req->execute(array('nouvelle' => 0,
						'ancienne' => $_GET['position']));
				
	$req->execute(array('nouvelle' => $_GET['position'],
						'ancienne' => $_GET['position'] - 1));
				
	$req->execute(array('nouvelle' => $_GET['position'] - 1,
						'ancienne' => 0));
}
// Descendre une classe
elseif($_GET['action'] == 'bas')
{
	$req = $bdd->prepare("UPDATE `CLASSE`
						SET `POSITION` = :nouvelle
						WHERE POSITION = :ancienne") or die(print_r($bdd->errorInfo()));
				
	$req->execute(array('nouvelle' => 0,
						'ancienne' => $_GET['position']));
				
	$req->execute(array('nouvelle' => $_GET['position'],
						'ancienne' =>	 $_GET['position'] + 1));
				
	$req->execute(array('nouvelle' => $_GET['position'] + 1,
						'ancienne' => 0));
}
// Effacer une classe et les étudiants associés
elseif($_GET['action'] == 'effacer')
{
	// Effacer la classe
	$req = $bdd->prepare("DELETE FROM CLASSE
					WHERE ID_CLASSE = ?") or die(print_r($bdd->errorInfo()));

	$req->execute(array($_GET['id']));	
				
	// Effacer les étudiants associés à la classe
	$req = $bdd->prepare("DELETE FROM ETUDIANT
					WHERE ID_CLASSE = ?");

	$req->execute(array($_GET['id']));
				
	// Effacer le lien Classe-Professeurs
	
	$req = $bdd->prepare("DELETE FROM ENSEIGNER
					WHERE ID_CLASSE = ?");

	$req->execute(array($_GET['id']));

	$typeMessage = 'effacer';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Administration des classes</title>
<link href="../styles.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/classes.js"></script>
</head>

<body>
<div class="site">
	<div class="header">
    	<object type="application/x-shockwave-flash" data="../intro/header.swf" width="969" height="159">
			<param name="play" value="true" />
			<param name="movie" value="../intro/header.swf" />
			<param name="menu" value="false" />
			<param name="quality" value="high" />
			<param name="scalemode" value="showall" />
		</object>
	</div>
    <div class="corp">
    	<div class="fondHaut"></div>
    	<div class="fondMilieu">
    		<div class="titrePage">Administration des classes</div>
            <div class="contenu" style="text-align:center">
            	Modifier:
                <a href="index.php?page=etudiants">Etudiants</a> |
                <a href="index.php?page=classes">Classes</a> |
                <a href="index.php?page=professeurs">Professeurs</a> |
                <a href="index.php?page=associations">Associations</a>
			</div>
            <br />
<?php		// Afficher les champs si l'action est Ajouter ou Modifier
			if($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')
			{
				// Si formulaire validé
				if($_POST['bouton'] != '')
				{
					// Générer la requête SQL selon l'action
					if($_GET['action'] == 'ajouter')
					{
						// Compter le nombre d'enregistrements existants
						$sqlPosition = $bdd->query("SELECT *
													FROM CLASSE
													ORDER BY POSITION") or die(print_r($bdd->errorInfo()));
						
						$position = $sqlPosition->rowCount() + 1;
							
						$req = $bdd->prepare("INSERT INTO `CLASSE` (`ID_CLASSE` ,`POSITION` ,`LIBELLE_CLASSE`)
											VALUES (NULL , :position, :libelleClasse);");
						
						$req->execute(array('position' => $position,
											'libelleClasse' => $_POST['libelleClasse']
											)) or die(print_r($bdd->errorInfo()));
					}
					elseif($_GET['action'] == "modifier")
					{
						$req = $bdd->prepare("UPDATE `CLASSE`
											 SET `LIBELLE_CLASSE` = :libelleClasse
											 WHERE `ID_CLASSE` = :idClasse");
							
						$req->execute(array('libelleClasse' => $_POST['libelleClasse'],
											'idClasse' => $_GET['id']
											));
					}	?>
                    <script type="text/javascript">
					document.location.href="index.php?page=classes&message=<?php echo $_GET['action']; ?>"
					</script>
<?php				}
				// Sinon, on l'affiche
				else
				{
					// Récupérer les infos enregistrées si l'action est Modifier
					if($_GET['action'] == 'modifier')
					{
						$req = $bdd->prepare("SELECT *
											FROM CLASSE
											WHERE ID_CLASSE = ?") or die(print_r($bdd->errorInfo()));
						
						$req->execute(array($_GET['id']));
													  
						$classe = $req->fetch();
					}	?>
            		<form id="formulaire" name="formulaire" action="index.php?page=classes&amp;action=<?php echo $_GET['action']; ?>&amp;id=<?php echo $_GET['id']; ?>" onsubmit="return verification()" method="post" enctype="application/x-www-form-urlencoded">
						<div class="contenu" style="text-align:center">
							<input type="text" id="libelleClasse" name="libelleClasse" class="libelleClasse" value="<?php echo $classe['LIBELLE_CLASSE']; ?>" size="32" maxlength="32" />
							<input type="submit" id="bouton" name="bouton" class="bouton" value="Valider" />
					 	 </div>
                	</form>
<?php			}	?>
			<br />
<?php		}
			// Affichage des messages du type "Action effectuée"
			if(isset($typeMessage) == false)
			{
				$typeMessage = $_GET['message'];
			}
				
			$message = '';
				
			if($typeMessage == 'ajouter')	$message = 'La classe a été ajoutée avec succès.';
			elseif($typeMessage == 'modifier')	$message = 'La classe a été modifiée avec succès.';
			elseif($typeMessage == 'effacer') $message = 'La classe et ses liens ont été effacés avec succès.';
				
			if(empty($message) == false)
			{	?>
                <div id="message" class="message"><?php echo $message; ?></div>
				<script type="text/javascript">setTimeout("document.getElementById('message').style.display = 'none'", 5000);</script>
<?php		}	?>
            
            <div class="contenu" style="text-align:center"><a href="index.php?page=classes&amp;action=ajouter">Ajouter une classe</a></div>
            <br />
<?php		
			$sql = $bdd->query("SELECT *
								FROM CLASSE
								ORDER BY POSITION") or die(print_r($bdd->errorInfo()));

			if($sql->rowCount() == 0)
			{	?>
            	<div class="contenu" style="text-align:center">*** Aucune classe pour l'instant ***</div>
<?php		}
			else
			{	?>
     			<table class="admin" style="width:250px" cellpadding="0" cellspacing="0">       
<?php			while($classe = $sql->fetch())
				{	?>
                	<tr>
                    	<td valign="top" style="width: 150px"><div class="contenu"><?php echo $classe['LIBELLE_CLASSE']; ?></div></td>
                       	 <td class="adminBoutons">
<?php					if($classe['POSITION'] > 1)
						{	?>
                        	<div class="contenu">
                        		<a href="index.php?page=classes&amp;action=haut&amp;position=<?php echo $classe['POSITION']; ?>">
                        			<img src="images/haut.png" width="16" height="18" border="0" alt="Remonter" title="Remonter" />
                            	</a>
                            </div>
<?php					}	?>
						</td>
                        <td class="adminBoutons">
<?php					if($classe['POSITION'] < $sql->rowCount())
						{	?>
                        	<div class="contenu">
                        		<a href="index.php?page=classes&amp;action=bas&amp;position=<?php echo $classe['POSITION']; ?>">
                        			<img src="images/bas.png" width="16" height="18" border="0" alt="Descendre" title="Descendre" />
                            	</a>
                           	 </div>
<?php					}	?>
						</td>
                        <td class="adminBoutons">
                        	<div class="contenu">
                        		<a href="index.php?page=classes&amp;action=modifier&amp;id=<?php echo $classe['ID_CLASSE']; ?>">
                        			<img src="images/edit.png" width="16" height="16" border="0" alt="Modifier" title="Modifier"/>
                            	</a>
                           	</div>
						</td>
                        <td class="adminBoutons">
<?php					// Voir si aucun étudiant de la classe n'est membre d'une association (MA = membre d'association)
						$sqlMA = $bdd->query("SELECT COUNT(*)
											FROM ETUDIANT e, ASSOCIATION a
											WHERE ID_CLASSE = ".$classe['ID_CLASSE']."
											AND (e.ID_ETUDIANT = PRESIDENT
											OR e.ID_ETUDIANT = VICE_PRESIDENT
											OR e.ID_ETUDIANT = SECRETAIRE
											OR e.ID_ETUDIANT = TRESORIER
											)") or die(print_r($bdd->errorInfo()));
							
						$reponseMA = $sqlMA->fetch();
?>
                        	<div class="contenu">
<?php							if($reponseMA['COUNT(*)'] == 0)
								{	?>
                                    <a href="index.php?page=classes&amp;action=effacer&amp;id=<?php echo $classe['ID_CLASSE']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer cette classe ainsi que les étudiants associés à celle-ci ?')">
                       					<img src="images/delete.png" width="16" height="16" border="0" alt="Supprimer" title="Supprimer" />
									</a>
<?php							}
								else
								{	?>
                                    <a href="#" onclick="alert('Cette classe possède des membres d\'associations. Elle ne peut être supprimée.')">
                                    	<img src="images/nodelete.png" width="16" height="16" border="0" alt="Suppression impossible" title="Suppression impossible"/>
                                       </a>
<?php							}	?>
                            </div>
						</td>
                    </tr>
<?php			}	?>
				</table>
<?php		}	?>
        </div>
   		<div class="fondBas"></div>
    </div>
    <div class="footer">Création par Jean-Baptiste Vigneron | EPSI Arras CPI1 2009-2010</div>
</div>

</body>
</html>