<?php
if($_GET['action'] == 'effacer')
{
	// Effacer l'étudiant
	$req = $bdd->prepare("DELETE FROM PROFESSEUR WHERE ID_PROF = ?");
	$req->execute(array($_GET['id']));
}	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Administration des professeurs</title>
<script type="text/javascript" src="js/professeurs.js"></script>
<link href="../styles.css" media="screen" rel="stylesheet" type="text/css" />
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
    		<div class="titrePage">Administration des professeurs</div>
            <div class="contenu" style="text-align:center">
            	Modifier:
                <a href="index.php?page=etudiants">Etudiants</a> |
                <a href="index.php?page=classes">Classes</a> |
                <a href="index.php?page=professeurs">Professeurs</a> |
                <a href="index.php?page=associations">Associations</a>
			</div>
            <br />
<?php		// Affichage des messages du type "Action effectuée"
			$typeMessage = $_GET['message'];
				
			$message = '';
				
			if($typeMessage == 'ajouter')
			{
				$message = 'Le professeur a été ajouté avec succès.';
			}
			elseif($typeMessage == 'modifier')
			{
				$message = 'Le professeur a été modifiée avec succès.';
			}
			elseif($typeMessage == 'effacer')
			{
				$message = 'Le professeur a été effacé avec succès.';
			}
				
			// Afficher un message si celui-ci a été indiqué
			if(empty($message) == false)
			{	?>
            	<div id="message" class="message"><?php echo $message; ?></div>
				<script type="text/javascript">setTimeout("document.getElementById('message').style.display = 'none'", 5000);</script>
<?php		}

			// Proposer le lien "Ajouter un professeur" s'il existe des classes
			$sqlClasses = $bdd->query("SELECT ID_CLASSE
										FROM CLASSE") or die(print_r($bdd->errorInfo()));
			
			if($sqlClasses->rowCount() == 0)
			{	?>
            	<div class="contenu" style="text-align:center">*** Veuillez ajouter des classes dans un 1er temps ***</div>
<?php		}
			else
			{	?>
            <div class="contenu" style="text-align:center"><a href="index.php?page=professeurs&amp;action=ajouter">Ajouter un professeur</a></div>
<?php		}	?>
            <br />
<?php		// Action: Ajouter/Modifier un étudiant
			if($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')
			{
				// Si formulaire validé
				if($_POST['bouton'] != '')
				{
					// Générer la requête SQL selon l'action
					if($_GET['action'] == 'ajouter')
					{
						// Etape 1: ajouter le professeur et ses informations
						$sqlAjoutProf = $bdd->prepare("INSERT INTO `PROFESSEUR` (`ID_PROF` ,`NOM` ,`PRENOM` ,`PHOTO` ,`MATIERES_ENSEIGNEES`)
											VALUES ('', :nom, :prenom, :photo, :matieresEnseignees);");
						
						$sqlAjoutProf->execute(array(
												'nom' => $_POST['nom'],
												'prenom' => $_POST['prenom'],
												'photo' => $_POST['photo'],
												'matieresEnseignees' => $_POST['matieresEnseignees']
												));		
						
						// Mettre dans la variable 'prof' l'id du nouveau prof pour la requête d'ajout des classes (voir plus bas)
						$prof = $bdd->lastInsertId("SELECT ID_PROF
													FROM PROFESSEUR");
					}
					elseif($_GET['action'] == 'modifier')
					{
						// Etape 1: Modifier les informations sur le professeur
						$sqlModifProf = $bdd->prepare("UPDATE `PROFESSEUR`
													SET `NOM` = :nom,
													`PRENOM` = :prenom,
													`PHOTO` = :photo,
													`MATIERES_ENSEIGNEES` = :matieresEnseignees
													WHERE `ID_PROF` = :id");		
						
						$sqlModifProf->execute(array(
													'nom' => $_POST['nom'],
													'prenom' => $_POST['prenom'],
													'photo' => $_POST['photo'],
													'matieresEnseignees' => $_POST['matieresEnseignees'],
													'id' => $_GET['id']
												));
						
						// Etape 2: Effacer les classes associées au professeur pour les enregistrer ensuite
						$sqlEffacerClasses = $bdd->prepare("DELETE FROM ENSEIGNER
														   WHERE ID_PROF = ?");
						
						$sqlEffacerClasses->execute(array($_GET['id']));
						
						// Stocker dans la variable prof l'id du prof concerné pour la requête d'ajout des classes (voir plus bas)
						$prof = $_GET['id'];
					}
					
					/* Etape finale: Ajouter les classes dans lesquelles le prof enseigne
						1) Récupérer le nombre de classes qui sont enregistrées */
						$sqlNbClasses = $bdd->query("SELECT ID_CLASSE
													FROM CLASSE") or die(print_r($bdd->errorInfo()));
						
						// 2) Préparer la requête d'ajout
						$sqlAjoutClasse = $bdd->prepare("INSERT INTO `ENSEIGNER` (`ID_PROF` ,`ID_CLASSE`)
														VALUES (:prof, :classe);") or die(print_r($bdd->errorInfo()));
						
						// 3) Vérifier pour chaque classe existante si celle-ci a été cochée dans le formulaire
						for($i = 0; $i <= $sqlNbClasses->rowCount(); $i++)
						{
							// 4) Insérer la classe si celle-ci a été cochée
							if(empty($_POST['classe'.$i]) == false)
							{
								$sqlAjoutClasse->execute(array(
															   'prof' => $prof,
															   'classe' => $_POST['classe'.$i]
															));
							}
						}	?>
                    <script type="text/javascript">
					document.location.href="index.php?page=professeurs&message=<?php echo $_GET['action']; ?>"
					</script>
					
<?php			}
				// Sinon afficher le formulaire
				else
				{
					// Si l'action est Modifier, récupérer les informmations déja enregistrées
					if($_GET['action'] == 'modifier')
					{
						// Infos sur l'étudiant
						$sqlProf = $bdd->prepare("SELECT *
													FROM PROFESSEUR
													WHERE ID_PROF = ?");
							
						$sqlProf->execute(array($_GET['id']));
						$professeur = $sqlProf->fetch();
					}	?>
					<div id="erreur" class="erreur"></div>
					<form id="formulaire" name="formulaire" action="index.php?page=professeurs&amp;action=<?php echo $_GET['action']; ?>&amp;id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return verification()" enctype="application/x-www-form-urlencoded">
						<div class="formulaire">Nom: <input name="nom" id="nom" type="text" value="<?php echo $professeur['NOM']; ?>" maxlength="32" /></div>
						<div class="formulaire">Prénom: <input name="prenom" id="prenom" type="text" value="<?php echo $professeur['PRENOM']; ?>" maxlength="32" /></div>
                        <div class="formulaire">
                        	Matières enseignées:
                            <input name="matieresEnseignees" type="text" id="matieresEnseignees" value="<?php echo $professeur['MATIERES_ENSEIGNEES']; ?>" size="50" maxlength="255" />
                      </div>
                        <div class="formulaire">Classes:
<?php					// Afficher les classes
						$sqlClasses = $bdd->query("SELECT ID_CLASSE, LIBELLE_CLASSE
												  FROM CLASSE") or die(print_r($bdd->errorInfo()));
						
						$i = 0;

						// Vérifier quelles classes sont enseignées par ce professeur
						while($classe = $sqlClasses->fetch())
						{
							// Cocher la case si le professeur enseigne dans cette classe
							$sqlClassesEnseignees = $bdd->prepare("SELECT ID_CLASSE
													 			FROM ENSEIGNER
													 			WHERE ID_PROF = :prof
																AND ID_CLASSE = :classe");
							
							$sqlClassesEnseignees->execute(array(
																 'prof' => $_GET['id'],
																 'classe' => $classe['ID_CLASSE']
																));
							
							if($sqlClassesEnseignees->rowCount() == 1)
							{	?>
                            	<input name="classe<?php echo $i; ?>" type="checkbox" id="classe<?php echo $i; ?>" value="<?php echo $classe['ID_CLASSE']; ?>" checked="checked" />
								<?php echo $classe['LIBELLE_CLASSE']; ?>
<?php						}
							else
							{	?>
                            	<input name="classe<?php echo $i; ?>" type="checkbox" id="classe<?php echo $i; ?>" value="<?php echo $classe['ID_CLASSE']; ?>" />
								<?php echo $classe['LIBELLE_CLASSE']; ?>
<?php						}

							$i++;
						}	?>
                      </div>
                        <div>&nbsp;</div>  
					  <div style="text-align:center"><input name="bouton" id="bouton" type="submit" value="Valider"/></div>
		  </form>
<?php			}
			}
			// Aucune action demandée
			else
			{
            	$sqlProfs = $bdd->query("SELECT *
										FROM PROFESSEUR
										ORDER BY NOM ASC, PRENOM ASC") or die(print_r($bdd->errorInfo()));
		
				// Si il n'y a aucun professeur enregistré, on affiche un message
				if($sqlProfs->rowCount() == 0)
				{	?>
					<div class="contenu" style="text-align:center">*** Aucun professeur pour l'instant ***</div>
<?php			}
				// Sinon on affiche la liste
				else
				{	?>
					<table class="admin" style="width:300px" cellpadding="0" cellspacing="0">       
<?php				while($professeur = $sqlProfs->fetch())
					{	?>
						<tr>
							<td valign="top" style="width: 200px"><div class="contenu"><?php echo $professeur['NOM'].' '.$professeur['PRENOM']; ?></div></td>
							<td class="adminBoutons">
								<div class="contenu">
									<a href="index.php?page=professeurs&amp;action=modifier&amp;id=<?php echo $professeur['ID_PROF']; ?>">
										<img src="images/edit.png" width="16" height="16" border="0" alt="Modifier" />
									</a>
								</div>
							</td>
							<td class="adminBoutons">
								<div class="contenu">
									<a href="index.php?page=professeurs&amp;action=effacer&amp;id=<?php echo $professeur['ID_PROF']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer ce professeur ?')">
										<img src="images/delete.png" width="16" height="16" border="0" alt="Supprimer" />
									</a>
							</div>
                            </td>
                       </tr>
<?php				}	?>
					</table>
<?php			}
			}	?>
			<div id="affichage"></div>
        </div>
   		<div class="fondBas"></div>
    </div>
    <div class="footer">Création par Jean-Baptiste Vigneron | EPSI Arras CPI1 2009-2010</div>
</div>

</body>
</html>