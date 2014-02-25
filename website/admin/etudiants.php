<?php
if($_GET['action'] == 'effacer')
{
	// Effacer l'étudiant
	$req = $bdd->prepare("DELETE FROM ETUDIANT WHERE ID_ETUDIANT = ?");
	$req->execute(array($_GET['id']));
}	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Administration des étudiants</title>
<script type="text/javascript" src="../js/ajax.js"></script>
<script type="text/javascript" src="js/etudiants.js"></script>
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
    		<div class="titrePage">Administration des étudiants</div>
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
				$message = 'L\'étudiant a été ajouté avec succès.';
			}
			elseif($typeMessage == 'modifier')
			{
				$message = 'L\'étudiant a été modifiée avec succès.';
			}
			elseif($typeMessage == 'effacer')
			{
				$message = 'L\'étudiant a été effacé avec succès.';
			}
				
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
            	<div class="contenu" style="text-align:center"><a href="index.php?page=etudiants&amp;action=ajouter">Ajouter un étudiant</a></div>
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
						$req = $bdd->prepare("INSERT INTO `ETUDIANT` (`ID_ETUDIANT` ,`ID_CLASSE` ,`NOM` ,`PRENOM` ,`PHOTO` ,`DATE_DE_NAISSANCE` ,`VILLE_ORIGINE` ,`EMAIL`)
											VALUES ('', :classe, :nom, :prenom, :photo, :dateDeNaissance, :villeOrigine, :email);");
						
						$req->execute(array(
										'classe' => $_POST['classe'],
										'nom' => $_POST['nom'],
										'prenom' => $_POST['prenom'],
										'photo' => $_POST['photo'],
										'dateDeNaissance' => $_POST['annee'].'-'.$_POST['mois'].'-'.$_POST['jour'],
										'villeOrigine' => $_POST['villeOrigine'],
										'email' => $_POST['email']
										));
					}
					elseif($_GET['action'] == 'modifier')
					{
						$req = $bdd->prepare("UPDATE `ETUDIANT`
											SET `ID_CLASSE` = :classe,
											`NOM` = :nom,
											`PRENOM` = :prenom,
											`PHOTO` = :photo,
											`DATE_DE_NAISSANCE` = :dateDeNaissance,
											`VILLE_ORIGINE` = :villeOrigine,
											`EMAIL` = :email
											WHERE `ID_ETUDIANT` = :id;");		
						
						$req->execute(array(
										'classe' => $_POST['classe'],
										'nom' => $_POST['nom'],
										'prenom' => $_POST['prenom'],
										'photo' => $_POST['photo'],
										'dateDeNaissance' => $_POST['annee'].'-'.$_POST['mois'].'-'.$_POST['jour'],
										'villeOrigine' => $_POST['villeOrigine'],
										'email' => $_POST['email'],
										'id' => $_GET['id']
										));
					}	?>
                    <script type="text/javascript">
					document.location.href="index.php?page=etudiants&message=<?php echo $_GET['action']; ?>"
					</script>
					
<?php			}
				// Sinon afficher le formulaire
				else
				{
					// Si l'action est Modifier, récupérer les informmations déja enregistrées
					if($_GET['action'] == 'modifier')
					{
						// Infos sur l'étudiant
						$sqlEtudiants = $bdd->prepare("SELECT *
													FROM ETUDIANT e, CLASSE c
													WHERE ID_ETUDIANT = ?
													AND e.ID_CLASSE = c.ID_CLASSE");
							
						$sqlEtudiants->execute(array($_GET['id']));
						$etudiant = $sqlEtudiants->fetch();
					}	?>
					<div id="erreur" class="erreur"></div>
					<form id="formulaire" name="formulaire" action="index.php?page=etudiants&amp;action=<?php echo $_GET['action']; ?>&amp;id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return verification()" enctype="application/x-www-form-urlencoded">
						<div class="formulaire">Nom: <input name="nom" id="nom" type="text" value="<?php echo $etudiant['NOM']; ?>" maxlength="32" /></div>
						<div class="formulaire">Prénom: <input name="prenom" id="prenom" type="text" value="<?php echo $etudiant['PRENOM']; ?>" maxlength="32" /></div>
						<div class="formulaire">
							Classe:
							<select name="classe" id="classe">
								<option value="<?php echo $etudiant['ID_CLASSE']; ?>"><?php echo $etudiant['LIBELLE_CLASSE']; ?></option>
								<option>------</option>
<?php
								$sqlClasses = $bdd->query("SELECT * FROM CLASSE") or die(print_r($bdd->errorInfo()));
								while($classe = $sqlClasses->fetch())
								{	?>
									<option value="<?php echo $classe['ID_CLASSE']; ?>"><?php echo $classe['LIBELLE_CLASSE']; ?></option>
<?php							} ?>
							</select>
						</div>
						<div class="formulaire">
							Date de naissance:
							<select name="jour" id="jour">
								<option value="<?php echo substr($etudiant['DATE_DE_NAISSANCE'], 8, 2); ?>"><?php echo substr($etudiant['DATE_DE_NAISSANCE'], 8, 2); ?></option>
								<option>------</option>
<?php							for($i = 1; $i <= 31; $i++)
								{
									if($i < 10)
									{
										$i = '0'.$i;
									}	?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php							} ?>
							</select>
							<select name="mois" id="mois">
                        		<option value="<?php echo substr($etudiant['DATE_DE_NAISSANCE'], 5, 2); ?>"><?php echo substr($etudiant['DATE_DE_NAISSANCE'], 5, 2); ?></option>
                            	<option>------</option>
<?php							for($i = 1; $i <= 12; $i++)
								{
									if($i < 10)
									{
										$i = '0'.$i;
									}	?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php							} ?>
							</select>
							<select name="annee" id="annee">
								<option value="<?php echo substr($etudiant['DATE_DE_NAISSANCE'], 0, 4); ?>"><?php echo substr($etudiant['DATE_DE_NAISSANCE'], 0, 4); ?></option>
								<option>------</option>
<?php							for($i = 1995; $i >= 1980; $i--)
								{ ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php							} ?>
							</select>
						</div>
						<div class="formulaire">
							Photo:
							<select name="photo" id="photo">
								<option><?php echo $etudiant['PHOTO']; ?></option>
								<option>------</option>
<?php		  					// Afficher les éléments contenus dans le dossier /photos/
								$dossier = opendir('../photos/');
   								while(($fichier = readdir($dossier)) != false)
								{
									if($fichier != "." && $fichier != "..")
									{ ?>
        								<option value="photos/<?php echo $fichier; ?>">photos/<?php echo $fichier; ?></option>
<?php   							}
								}
   								closedir($dossier); ?>
							</select>
						</div>
						<div class="formulaire">
                        	Ville d'origine: <input name="villeOrigine" id="villeOrigine" type="text" value="<?php echo $etudiant['VILLE_ORIGINE']; ?>" />
						</div>
						<div class="formulaire">Email: <input name="email" id="email" type="text" value="<?php echo $etudiant['EMAIL']; ?>" /></div>
						<br />
						<div style="text-align:center"><input name="bouton" id="bouton" type="submit" value="Valider"/></div>
					</form>
<?php			}
			}
			// Aucune action demandée
			else
			{
				// Afficher toutes les classes dans un select
				$sqlClasses = $bdd->query("SELECT * FROM CLASSE ORDER BY POSITION") or die(print_r($bdd->errorInfo()));	
				if($sqlClasses->rowCount() > 0)
				{	?>
            		<form name="formulaire" id="formulaire" action="" method="post" enctype="application/x-www-form-urlencoded">
                		<div class="contenu" style="text-align:center">
                    		Classe:
							<select name="classe" class="classe" onchange="afficher()">
                            	<option value="0">Sélectionnez une classe...</option>
<?php							while($classe = $sqlClasses->fetch())
								{	?>
									<option value="<?php echo $classe['ID_CLASSE']; ?>"><?php echo $classe['LIBELLE_CLASSE']; ?></option>
<?php							} ?>
							</select>
            			</div>
            		</form>
               		 <br />
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