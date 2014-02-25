<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EPSITrombi' :: Trombinoscope des professeurs</title>
<link href="styles.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/ajax.js"></script>
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
		</object>
	</div>
    <div class="menu"><a href="index.php">Accueil</a> </div>	
    <div class="menu"><a href="index.php?page=etudiants">Etudiants</a></div>
	<div class="menu"><a href="index.php?page=professeurs">Professeurs</a></div>
	<div class="menu"><a href="index.php?page=associations">Associations</a></div>
    <div class="corp">
    	<div class="fondHaut"></div>
    	<div class="fondMilieu">
        	<div class="titrePage">Trombinoscope des professeurs</div>
            <br />
			<div id="affichage"><script type="text/javascript">ajax('', 'ajax/professeurs.php', 'POST', 'affichage')</script></div>
		</div>
   		<div class="fondBas"></div>
    </div>
    <div class="footer">Création par Jean-Baptiste Vigneron | EPSI Arras CPI1 2009-2010</div>
</div>

</body>
</html>