<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../sql.php");

$classe = $_POST['classe'];
$etudiant = $_POST['etudiant'];

// On vérifie si c'est une classe qui est demandée
if(empty($classe) == false && empty($etudiant) == true)
{
	// Récupérer les informations des étudiants de cette classe
	$sqlNbEtudiants = $bdd->query("SELECT *
								  FROM ETUDIANT
								  WHERE ID_CLASSE = ".$classe."
								  ORDER BY NOM ASC, PRENOM ASC") or die(print_r($bdd->errorInfo()));
	
	
	$nbEtudiants = $sqlNbEtudiants->rowCount();
	
	// Si il n'y a aucun étudiant pour cette classe, on affiche un message
	if($nbEtudiants == 0)
	{	?>
        <div class="contenu" style="text-align:center">*** Aucun étudiant pour cette classe ***</div>
<?php
	}
	// Sinon on affiche le trombinoscope sous forme de tableau
	else
	{	?>
		<div class="resultat">
			<table class="trombi">
<?php		// Savoir combien de lignes il faut pour afficher les étudiants (5 étudiants par ligne)
			$nbLignes = ceil($nbEtudiants / 5);
			
			//Initialisation de 2 variables (indice actuelle de l'étudiant et indice du dernier à afficher pour chaque ligne)
			$j = 0;
			$maxLigne = 4;
				
			// On affiche toutes les lignes
			for($i = 1; $i <= $nbLignes; $i++)
			{	?>
            	<tr class="trombi">
<?php				// Sur chaque ligne, on affiche les 5 suivants
					for($j = $j; $j <= $maxLigne; $j++)
					{
						$sqlEtudiants = $bdd->query("SELECT *
													FROM ETUDIANT
													WHERE ID_CLASSE = ".$classe."
													ORDER BY NOM LIMIT ".$j.", 1") or die(print_r($bdd->errorInfo()));
						
						if($etudiant = $sqlEtudiants->fetch())
						{
							// Voir si une photo pour l'étudiant existe
							if(file_exists('../'.$etudiant['PHOTO']) == true)
							{
								$photo = $etudiant['PHOTO']; 
							}
							// Sinon afficher l'image par défaut
							else
							{
								$photo = 'images/noPhoto.png';
							}	?>
                			<td class="trombi">
                        		<div class="nomPrenom">
                                    <a href="#" onclick="ajax('etudiant=<?php echo $etudiant['ID_ETUDIANT']; ?>', 'ajax/etudiants.php', 'POST', 'affichage')">
                                        <img src="<?php echo $photo; ?>" border="0" width="140" height="140" />
									</a>
								</div>
								<div class="nomPrenom"><?php echo $etudiant['PRENOM'].' '.$etudiant['NOM']; ?></div>
							</td>
<?php					}
					}
					// Incrémenter le nombre maximum de 5 pour la ligne suivante
					$maxLigne += 5;	?>
				</tr>
<?php		} ?>     
        	</table>
		</div>
<?php
	}
}
// Sinon on vérifie si c'est un étudiant qui est demandé
elseif(empty($classe) == true && empty($etudiant) == false)
{
	// Récupérer les informations de l'étudiant
	$sqlEtudiants = $bdd->query("SELECT *
								FROM ETUDIANT e, CLASSE c
								WHERE e.ID_ETUDIANT = ".$etudiant."
								AND e.ID_CLASSE = c.ID_CLASSE") or die(print_r($bdd->errorInfo()));
				
	// Afficher si l'étudiant avec cet id existe
	if($etudiant = $sqlEtudiants->fetch())
	{
		// Vérifier si la photo enregistrée existe
		if(file_exists('../'.$etudiant['PHOTO']))
		{
			$photo = $etudiant['PHOTO']; 
		}
		else
		{
			$photo = 'images/noPhoto.png';
		}	?>
    <br />
    <table class="personne" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="imagePersonne"><img src="<?php echo $photo; ?>" border="0" width="140" height="140" /></td>
			<td class="textePersonne">
				<div class="titre"><?php echo $etudiant['PRENOM'].' '.$etudiant['NOM']; ?></div>
				<div class="contenu">Classe: <?php echo $etudiant['LIBELLE_CLASSE']; ?></div>
				<div class="contenu">Date de naissance: <?php echo dateFR($etudiant['DATE_DE_NAISSANCE']); ?></div>
				<div class="contenu">
					Ville d'origine:
					<a href="http://maps.google.fr/maps?f=d&source=s_d&saddr=EPSI+Arras&daddr=<?php echo $etudiant['VILLE_ORIGINE']; ?>" target="_blank">
						<?php echo $etudiant['VILLE_ORIGINE']; ?>
					</a>
				</div>
				<div class="contenu">Email: <a href="mailto:<?php echo $etudiant['EMAIL']; ?>"><?php echo $etudiant['EMAIL']; ?></a></div>
				<br />
			</td>
		</tr>
	</table>
    <br />
    <div class="contenu" style="text-align:center">
		<a href="#" onclick="ajax('classe=<?php echo $etudiant['ID_CLASSE']; ?>', 'ajax/etudiants.php', 'POST', 'affichage')">
			Retour à la liste des <?php echo $etudiant['LIBELLE_CLASSE']; ?>
        </a>
	</div>
<?php
	}
}
?>