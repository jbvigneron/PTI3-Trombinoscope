<?php
if($_GET['action'] == 'effacer')
{
	// Effacer l'étudiant
	$req = $bdd->prepare("DELETE FROM ASSOCIATION WHERE ID_ASSOCIATION = ?");
	$req->execute(array($_GET['id']));
}	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Administration des associations</title>
<script type="text/javascript" src="js/associations.js"></script>
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
    		<div class="titrePage">Administration des associations</div>
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
				$message = 'L\'association a été ajouté avec succès.';
			}
			elseif($typeMessage == 'modifier')
			{
				$message = 'L\'association a été modifiée avec succès.';
			}
			elseif($typeMessage == 'effacer')
			{
				$message = 'L\'association a été effacé avec succès.';
			}
				
			// Afficher un message si celui-ci a été indiqué
			if(empty($message) == false)
			{	?>
            	<div id="message" class="message"><?php echo $message; ?></div>
				<script type="text/javascript">setTimeout("document.getElementById('message').style.display = 'none'", 5000);</script>
<?php		}

			// Proposer le lien "Ajouter une association" s'il existe des étudiants
			$sqlClasses = $bdd->query("SELECT ID_ETUDIANT
										FROM ETUDIANT") or die(print_r($bdd->errorInfo()));
			
			if($sqlClasses->rowCount() == 0)
			{	?>
            	<div class="contenu" style="text-align:center">*** Veuillez ajouter des étudiants dans un 1er temps ***</div>
<?php		}
			else
			{	?>
            <div class="contenu" style="text-align:center"><a href="index.php?page=associations&amp;action=ajouter">Ajouter une association</a></div>
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
						$sqlAjout = $bdd->prepare("INSERT INTO `ASSOCIATION` (`ID_ASSOCIATION` ,`NOM` ,`IMAGE` ,`SITE` ,`PRESIDENT` ,`VICE_PRESIDENT` ,`SECRETAIRE` ,`TRESORIER`)
											VALUES ('', :nom, :image, :site, :president, :vicePresident, :secretaire, :tresorier);");
						
						$sqlAjout->execute(array(
												'nom' => $_POST['nom'],
												'image' => $_POST['image'],
												'site' => $_POST['site'],
												'president' => $_POST['president'],
												'vicePresident' => $_POST['vicePresident'],
												'secretaire' => $_POST['secretaire'],
												'tresorier' => $_POST['tresorier'],
												));		
					}
					elseif($_GET['action'] == 'modifier')
					{
						// Etape 1: Modifier les informations sur l'association
						$sqlModif = $bdd->prepare("UPDATE `ASSOCIATION`
												SET `NOM` = :nom,
												`IMAGE` = :image,
												`SITE` = :site,
												`PRESIDENT` = :president,
												`VICE_PRESIDENT` = :vicePresident,
												`SECRETAIRE` = :secretaire,
												`TRESORIER` = :tresorier
												WHERE `ID_ASSOCIATION` = :id");		
						
						$sqlModif->execute(array(
												'nom' => $_POST['nom'],
												'image' => $_POST['image'],
												'site' => $_POST['site'],
												'president' => $_POST['president'],
												'vicePresident' => $_POST['vicePresident'],
												'secretaire' => $_POST['secretaire'],
												'tresorier' => $_POST['tresorier'],
												'id' => $_GET['id']
											));
					}	?>
                    <script type="text/javascript">
					document.location.href="index.php?page=associations&message=<?php echo $_GET['action']; ?>"
					</script>
					
<?php			}
				// Sinon afficher le formulaire
				else
				{
					// Si l'action est Modifier, récupérer les informmations déja enregistrées
					if($_GET['action'] == 'modifier')
					{
						// Infos sur l'association
						$sqlAsso = $bdd->prepare("SELECT a.NOM, a.IMAGE, a.SITE,
												p.ID_ETUDIANT AS ID_PRESIDENT, p.NOM AS NOM_PRESIDENT, p.PRENOM AS PRENOM_PRESIDENT,
												vp.ID_ETUDIANT AS ID_VP, vp.NOM AS NOM_VP, vp.PRENOM AS PRENOM_VP,
												s.ID_ETUDIANT AS ID_SECRETAIRE, s.NOM AS NOM_SECRETAIRE, s.PRENOM AS PRENOM_SECRETAIRE,
												T.ID_ETUDIANT AS ID_TRESORIER, t.NOM AS NOM_TRESORIER, t.PRENOM AS PRENOM_TRESORIER
												FROM ASSOCIATION a, ETUDIANT p, ETUDIANT vp, ETUDIANT s, ETUDIANT t
												WHERE a.PRESIDENT = p.ID_ETUDIANT
												AND a.VICE_PRESIDENT = vp.ID_ETUDIANT
												AND a.SECRETAIRE = s.ID_ETUDIANT
												AND a.TRESORIER = t.ID_ETUDIANT
												AND ID_ASSOCIATION = ?") or die(print_r($bdd->errorInfo()));
							
						$sqlAsso->execute(array($_GET['id']));
						$asso = $sqlAsso->fetch();
					}	?>
					<div id="erreur" class="erreur"></div>
					<form id="formulaire" name="formulaire" action="index.php?page=associations&amp;action=<?php echo $_GET['action']; ?>&amp;id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return verification()" enctype="application/x-www-form-urlencoded">
						<div class="formulaire">Nom de l'association: <input name="nom" type="text" id="nom" value="<?php echo $asso['NOM']; ?>" size="32" maxlength="32" /></div>
						<div class="formulaire">
                        	Image:
                        	<select name="image" id="image">
									<option><?php echo $asso['IMAGE']; ?></option>
									<option>------</option>
<?php		  						// Afficher les éléments contenus dans le dossier /associations/
									$dossier = opendir('../associations/');
   									while(($fichier = readdir($dossier)) != false)
									{
										if($fichier != "." && $fichier != "..")
										{ ?>
        									<option value="associations/<?php echo $fichier; ?>">associations/<?php echo $fichier; ?></option>
<?php   								}
									}
   								closedir($dossier); ?>
							</select>
                        </div>
                        <div class="formulaire">Site web: <input name="site" type="text" id="site" value="<?php echo $asso['SITE']; ?>" size="40" maxlength="128" /></div>
                        <div>&nbsp;</div>
<div class="formulaire">
                        	Président: 
                        	<select name="president" id="president">
									<option value="<?php echo $asso['ID_PRESIDENT']; ?>">
										<?php echo $asso['NOM_PRESIDENT']; ?> <?php echo $asso['PRENOM_PRESIDENT']; ?>
                              </option>
<?php		  						// Afficher les classes et les étudiants associés à chaque classe
									$sqlClasse = $bdd->query("SELECT ID_CLASSE, LIBELLE_CLASSE
															 FROM CLASSE
															 ORDER BY POSITION");
									
									while($classe = $sqlClasse->fetch())
									{	?>
                                    	<option></option>
                                    	<option class="classe"><?php echo $classe['LIBELLE_CLASSE'];	?></option>
<?php									
										$sqlEtudiants = $bdd->prepare("SELECT ID_ETUDIANT, NOM, PRENOM
																	  FROM ETUDIANT
																	  WHERE ID_CLASSE = ?
																	  ORDER BY NOM, PRENOM");
										
										$sqlEtudiants->execute(array($classe['ID_CLASSE']));
										while($etudiant = $sqlEtudiants->fetch())
										{	?>
                                        	<option value="<?php echo $etudiant['ID_ETUDIANT']; ?>"><?php echo $etudiant['NOM']; ?> <?php echo $etudiant['PRENOM']; ?></option>
<?php									}
									}	?>
							</select>
                        </div>
                        <div class="formulaire">
                        	Vice-Président: 
                        	<select name="vicePresident" id="vicePresident">
									<option value="<?php echo $asso['ID_VP']; ?>">
										<?php echo $asso['NOM_VP']; ?> <?php echo $asso['PRENOM_VP']; ?>
                              </option>
<?php		  						// Afficher les classes et les étudiants associés à chaque classe
									$sqlClasse = $bdd->query("SELECT ID_CLASSE, LIBELLE_CLASSE
															 FROM CLASSE
															 ORDER BY POSITION");
									
									while($classe = $sqlClasse->fetch())
									{	?>
                                    	<option></option>
                                    	<option class="classe"><?php echo $classe['LIBELLE_CLASSE'];	?></option>
<?php									
										$sqlEtudiants = $bdd->prepare("SELECT ID_ETUDIANT, NOM, PRENOM
																	  FROM ETUDIANT
																	  WHERE ID_CLASSE = ?
																	  ORDER BY NOM, PRENOM");
										
										$sqlEtudiants->execute(array($classe['ID_CLASSE']));
										while($etudiant = $sqlEtudiants->fetch())
										{	?>
                                        	<option value="<?php echo $etudiant['ID_ETUDIANT']; ?>"><?php echo $etudiant['NOM']; ?> <?php echo $etudiant['PRENOM']; ?></option>
<?php									}
									}	?>
							</select>
                        </div>
                        <div class="formulaire">
                        	Secrétaire: 
                        	<select name="secretaire" id="secretaire">
									<option value="<?php echo $asso['ID_SECRETAIRE']; ?>">
										<?php echo $asso['NOM_SECRETAIRE']; ?> <?php echo $asso['PRENOM_SECRETAIRE']; ?>
                              </option>
<?php		  						// Afficher les classes et les étudiants associés à chaque classe
									$sqlClasse = $bdd->query("SELECT ID_CLASSE, LIBELLE_CLASSE
															 FROM CLASSE
															 ORDER BY POSITION");
									
									while($classe = $sqlClasse->fetch())
									{	?>
                                    	<option></option>
                                    	<option class="classe"><?php echo $classe['LIBELLE_CLASSE'];	?></option>
<?php									
										$sqlEtudiants = $bdd->prepare("SELECT ID_ETUDIANT, NOM, PRENOM
																	  FROM ETUDIANT
																	  WHERE ID_CLASSE = ?
																	  ORDER BY NOM, PRENOM");
										
										$sqlEtudiants->execute(array($classe['ID_CLASSE']));
										while($etudiant = $sqlEtudiants->fetch())
										{	?>
                                        	<option value="<?php echo $etudiant['ID_ETUDIANT']; ?>"><?php echo $etudiant['NOM']; ?> <?php echo $etudiant['PRENOM']; ?></option>
<?php									}
									}	?>
							</select>
                        </div>
                        <div class="formulaire">
                        	Trésorier: 
                        	<select name="tresorier" id="tresorier">
									<option value="<?php echo $asso['ID_TRESORIER']; ?>">
										<?php echo $asso['NOM_TRESORIER']; ?> <?php echo $asso['PRENOM_TRESORIER']; ?>
                              </option>
<?php		  						// Afficher les classes et les étudiants associés à chaque classe
									$sqlClasse = $bdd->query("SELECT ID_CLASSE, LIBELLE_CLASSE
															 FROM CLASSE
															 ORDER BY POSITION");
									
									while($classe = $sqlClasse->fetch())
									{	?>
                                    	<option></option>
                                    	<option class="classe"><?php echo $classe['LIBELLE_CLASSE'];	?></option>
<?php									
										$sqlEtudiants = $bdd->prepare("SELECT ID_ETUDIANT, NOM, PRENOM
																	  FROM ETUDIANT
																	  WHERE ID_CLASSE = ?
																	  ORDER BY NOM, PRENOM");
										
										$sqlEtudiants->execute(array($classe['ID_CLASSE']));
										while($etudiant = $sqlEtudiants->fetch())
										{	?>
                                        	<option value="<?php echo $etudiant['ID_ETUDIANT']; ?>"><?php echo $etudiant['NOM']; ?> <?php echo $etudiant['PRENOM']; ?></option>
<?php									}
									}	?>
							</select>
                        </div>
                        <div>&nbsp;</div>  
					  <div style="text-align:center"><input name="bouton" id="bouton" type="submit" value="Valider"/></div>
		 		</form>
<?php			}
			}
			// Aucune action demandée
			else
			{
            	$sqlAssos = $bdd->query("SELECT *
										FROM ASSOCIATION") or die(print_r($bdd->errorInfo()));
		
				// Si il n'y a aucun professeur enregistré, on affiche un message
				if($sqlAssos->rowCount() == 0)
				{	?>
					<div class="contenu" style="text-align:center">*** Aucune association pour l'instant ***</div>
<?php			}
				// Sinon on affiche la liste
				else
				{	?>
					<table class="admin" style="width:300px" cellpadding="0" cellspacing="0">       
<?php				while($asso = $sqlAssos->fetch())
					{	?>
						<tr>
							<td valign="top" style="width: 200px"><div class="contenu"><?php echo $asso['NOM']; ?></div></td>
							<td class="adminBoutons">
								<div class="contenu">
									<a href="index.php?page=associations&amp;action=modifier&amp;id=<?php echo $asso['ID_ASSOCIATION']; ?>">
										<img src="images/edit.png" width="16" height="16" border="0" alt="Modifier" />
									</a>
								</div>
							</td>
							<td class="adminBoutons">
								<div class="contenu">
									<a href="index.php?page=associations&amp;action=effacer&amp;id=<?php echo $asso['ID_ASSOCIATION']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer cette association ?')">
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