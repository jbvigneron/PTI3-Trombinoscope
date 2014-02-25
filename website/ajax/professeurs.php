<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../sql.php");

$prof = $_POST['prof'];

// Si aucun prof n'est sélectionné, on affiche le trombinoscope
if(empty($prof))
{
	$sqlProfs = $bdd->query("SELECT *
							FROM PROFESSEUR
							ORDER BY NOM ASC, PRENOM ASC") or die(print_r($bdd->errorInfo()));
	$nbProfs = $sqlProfs->rowCount();
		
	// Si il n'y a aucun étudiant pour cette classe, on affiche un message
	if($nbProfs == 0)
	{	?>
        <div class="contenu" style="text-align:center">*** Aucun professeur enregistré pour l'instant ***</div>
<?php
	}
	// Sinon on affiche le trombinoscope sous forme de tableau
	else
	{	?>
        <table class="trombi">
<?php	// Savoir combien de lignes il faut à partir du nombre d'enregistrements
		$nbLignes = ceil($nbProfs / 5);
			
		// Afficher 5 personnes par lignes. Initialisation de 2 variables (valeur de départ et valeur de fin)
		$j = 0;
		$max = 4;
				
		// On affiche toutes les lignes
		for($i = 1; $i <= $nbLignes; $i++)
		{	?>
            <tr class="trombi">
<?php		// Sur chaque ligne, on affiche les 5 suivants
			for($j = $j; $j <= $max; $j++)
			{
				if($professeur = $sqlProfs->fetch())
				{
					if(file_exists($professeur['PHOTO']) == true)
					{
						$photo = $professeur['PHOTO']; 
					}
					else
					{
						$photo = 'images/noPhoto.png';
					}	?>
                	<td class="trombi">
						<div class="nomPrenom">
							<a href="#" onclick="ajax('prof=<?php echo $professeur['ID_PROF']; ?>', 'ajax/professeurs.php', 'POST', 'affichage')">
								<img src="<?php echo $photo; ?>" border="0" width="140" height="140" />
							</a>
						</div>
						<div class="nomPrenom"><?php echo $professeur['PRENOM'].' '.$professeur['NOM']; ?></div>
					</td>
<?php			}
			}
			// Incrémenter le nombre maximum de 5 pour la ligne suivante
			$max += 5;	?>
			</tr>
<?php	} ?>     
        </table>
<?php	
	}
}
// Sinon, l'id du prof a été précisé
else
{
	// Récupérer les informations de l'étudiant
	$sqlProf = $bdd->query("SELECT *
						   FROM PROFESSEUR
						   WHERE ID_PROF = ".$prof) or die(print_r($bdd->errorInfo()));
				
	// Afficher si l'étudiant avec cet id existe
	if($professeur = $sqlProf->fetch())
	{
		// Vérifier si la photo enregistrée existe
		if(file_exists($professeur->PHOTO) == true)
		{
			$photo = $professeur->PHOTO; 
		}
		else
		{
			$photo = 'images/noPhoto.png';
		}	?>
		<table class="personne" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="imagePersonne"><img src="<?php echo $photo; ?>" border="0" width="140" height="140" /></td>
				<td class="textePersonne">
					<div class="titre"><?php echo $professeur['PRENOM'].' '.$professeur['NOM']; ?></div>
					<div class="contenu">Matières enseignées: <?php echo $professeur['MATIERES_ENSEIGNEES']; ?></div>
					<div class="contenu">
						Classes: 
<?php					// Afficher les classes ou professeur eenseigne
						$sqlClasse = $bdd->query("SELECT *
												 FROM ENSEIGNER e, CLASSE c
												 WHERE ID_PROF = ".$prof."
												 AND e.ID_CLASSE = c.ID_CLASSE
												 ORDER BY POSITION") or die(print_r($bdd->errorInfo()));
					
						// Un compteur pour afficher des virgules (ex:Classe1, Classe2, Classe3)
						$i = 1;
						$nbClasses = $sqlClasse->rowCount();
						
						while($classe = $sqlClasse->fetch())
						{	?>			
							<a href="index.php?page=etudiants&numClasse=<?php echo $classe['ID_CLASSE']; ?>"><?php echo $classe['LIBELLE_CLASSE'];	?></a>							

<?php						// Afficher une virgule s'il y a encore des classes derrière
							if($i < $nbClasses)	echo ', ';
							$i++;
						}	?>
					</div>	
				</td>
			</tr>
		</table>
        <br />
        <div class="contenu" style="text-align:center"><a href="#" onclick="ajax('', 'ajax/professeurs.php', 'POST', 'affichage')">Retour à la liste des professeurs</a></div>
<?php
	}
	else
	{	?>
		<div style="text-align:center">*** Désolé nous n'avons trouvé aucun professeur ***</div>
		<br />   
		<div class="contenu" style="text-align:center"><a href="index.php?page=professeurs">Retour à la liste des professeurs</a></div>         
<?php
	}	
}	?>