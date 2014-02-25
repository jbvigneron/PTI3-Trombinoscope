<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Trombinoscope des étudiants</title>
<link href="styles.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="site">
	<div class="header">
    	<object type="application/x-shockwave-flash" data="intro/header.swf" width="969" height="159">
			<param name="play" value="true" />
			<param name="movie" value="intro/header.swf" />
			<param name="menu" value="false" />
			<param name="quality" value="high" />
			<param name="scalemode" value="showall" />
		</object></div>
    <div class="menu"><a href="index.php">Accueil</a> </div>	
    <div class="menu"><a href="index.php?page=etudiants">Etudiants</a></div>
	<div class="menu"><a href="index.php?page=professeurs">Professeurs</a></div>
	<div class="menu"><a href="index.php?page=associations">Associations</a></div>
    <div class="corp">
    	<div class="fondHaut"></div>
    	<div class="fondMilieu">
<?php		// Si aucune asso n'est sélectionné, on affiche la liste
			if(isset($_POST['asso']))	$asso = $_POST['asso'];
			elseif(isset($_GET['asso']))	$asso = $_GET['asso'];
			else $asso = 0;	?>
    		<div class="titrePage">Les associations</div> 	
<?php		// Afficher toutes les assos dans un select
			$sqlAssos = $bdd->query("SELECT a.NOM, a.IMAGE, a.SITE,
									p.NOM AS NOM_PRESIDENT, p.PRENOM AS PRENOM_PRESIDENT,
									vp.NOM AS NOM_VP, vp.PRENOM AS PRENOM_VP,
									s.NOM AS NOM_SECRETAIRE, s.PRENOM AS PRENOM_SECRETAIRE,
									t.NOM AS NOM_TRESORIER, t.PRENOM AS PRENOM_TRESORIER
									FROM ASSOCIATION a, ETUDIANT p, ETUDIANT vp, ETUDIANT s, ETUDIANT t
									WHERE a.PRESIDENT = p.ID_ETUDIANT
									AND a.VICE_PRESIDENT = vp.ID_ETUDIANT
									AND a.SECRETAIRE = s.ID_ETUDIANT
									AND a.TRESORIER = t.ID_ETUDIANT") or die(print_r($bdd->errorInfo()));
					
			if($sqlAssos->rowCount() > 0)
			{	?>
                <table class="asso" cellpadding="5" cellspacing="5">
<?php            	while($association = $sqlAssos->fetch())
					{	?>
						<tr>
                            <td class="imageAsso">
                           		<div>
                                	<a href="<?php echo $association['SITE']; ?>" target="_blank">
										<img src="<?php echo $association['IMAGE']; ?>" width="150" height="150" border="0" alt="<?php echo $association['NOM']; ?>" />
									</a>
                       			</div>
							</td>
							<td class="texteAsso">
								<div class="titreAsso"><a href="<?php echo $association['SITE']; ?>" target="_blank"><?php echo $association['NOM']; ?></a></div>
								<div class="bureauAsso">Président: <?php echo $association['PRENOM_PRESIDENT']; ?> <?php echo $association['NOM_PRESIDENT']; ?></div>
                                <div class="bureauAsso">Vice-président: <?php echo $association['PRENOM_VP']; ?> <?php echo $association['NOM_VP']; ?></div>
                                <div class="bureauAsso">Secrétaire: <?php echo $association['PRENOM_SECRETAIRE']; ?> <?php echo $association['NOM_SECRETAIRE']; ?></div>
                                <div class="bureauAsso">Trésorier: <?php echo $association['PRENOM_TRESORIER']; ?> <?php echo $association['NOM_TRESORIER']; ?></div>
							</td>
						</tr>                           
<?php				} ?>
				</table>
<?php			}
				else
				{	?>
                	<div class="contenu" style="text-align:center">*** Il n'existe aucune association pour le moment ***</div>
<?php			}	?>
		</div>
   		<div class="fondBas"></div>
    </div>
    <div class="footer">Création par Jean-Baptiste Vigneron | EPSI Arras CPI1 2009-2010</div>
</div>

</body>
</html>