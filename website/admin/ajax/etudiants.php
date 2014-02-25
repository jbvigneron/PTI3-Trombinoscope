<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../../sql.php");

// Récupérer la classe
$classe = $_POST['classe'];

$sqlEtudiants = $bdd->prepare("SELECT *
							FROM ETUDIANT
							WHERE ID_CLASSE = ?
							ORDER BY NOM ASC, PRENOM ASC");

$sqlEtudiants->execute(array($_POST['classe']));
		
// Si il n'y a aucun étudiant pour cette classe, on affiche un message
if($sqlEtudiants->rowCount() == 0)
{	?>
	<div class="contenu" style="text-align:center">*** Aucun étudiant pour cette classe ***</div>
<?php
}
// Sinon on affiche la liste
else
{	?>
	<table class="admin" style="width:300px" cellpadding="0" cellspacing="0">       
<?php
	while($etudiant = $sqlEtudiants->fetch())
	{	?>
		<tr>
			<td valign="top" style="width: 200px"><div class="contenu"><?php echo $etudiant['NOM'].' '.$etudiant['PRENOM']; ?></div></td>
			<td class="adminBoutons">
				<div class="contenu">
					<a href="index.php?page=etudiants&action=modifier&id=<?php echo $etudiant['ID_ETUDIANT']; ?>">
						<img src="images/edit.png" width="16" height="16" border="0" />
					</a>
				</div>
			</td>
			<td class="adminBoutons">
<?php			// Vérifier s'il est membre d'une association (suppression impossible dans ce cas)
				$sqlAssociation = $bdd->query("SELECT NOM, COUNT(*), PRESIDENT, VICE_PRESIDENT, SECRETAIRE, TRESORIER
									 FROM ASSOCIATION
									 WHERE PRESIDENT = ".$etudiant['ID_ETUDIANT']."
									 OR VICE_PRESIDENT = ".$etudiant['ID_ETUDIANT']."
									 OR SECRETAIRE = ".$etudiant['ID_ETUDIANT']."
									 OR TRESORIER = ".$etudiant['ID_ETUDIANT']) or die(print_r($bdd->errorInfo()));
				
				$association = $sqlAssociation->fetch();

				// S'il ne fait partie d'aucune asso, proposer la suppression
				if($association['COUNT(*)'] == 0)
				{	?>
					<div class="contenu">
						<a href="index.php?page=etudiants&action=effacer&id=<?php echo $etudiant['ID_ETUDIANT']; ?>" onclick="return confirm('Etes-vous sur de vouloir supprimer cet étudiant ?')">
							<img src="images/delete.png" width="16" height="16" border="0" />
						</a>
					</div>
<?php			}
				// S'il fait partie d'une asso, empêcher la suppression
				else
				{
					// Génération de l'alert en cas de clic sur l'icone "Supprimer"
					// Alert: "Cet étudiant est +ROLE+ de l\'association +NOM+. Êtes-vous sur de vouloir le supprimer ?"
					if($association['PRESIDENT'] == $etudiant['ID_ETUDIANT'])
					{
						$role = 'président';
					}
					elseif($association['VICE_PRESIDENT'] == $etudiant['ID_ETUDIANT'])
					{
						$role = 'vice-président'; 
					}
					elseif($association['SECRETAIRE'] == $etudiant['ID_ETUDIANT'])
					{
						$role = 'secrétaire';
					}
					elseif($association['TRESORIER'] == $etudiant['ID_ETUDIANT'])
					{
						$role = 'trésorier';
					}	?>
					<div class="contenu">
						<a href="#" onclick="alert('Cet étudiant est <?php echo $role; ?> de l\'association <?php echo $association['NOM']; ?>.\nVous ne pouvez pas le supprimer.')">
							<img src="images/nodelete.png" width="16" height="16" border="0" />
						</a>
					</div>
<?php			}	?>
			</td>
		</tr>
<?php
	}	?>
	</table>
<?php
} ?>