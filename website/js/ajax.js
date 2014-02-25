// AJAX
function ajax(donnees, url, methode, cadre)
{
	// Afficher l'image de chargement
	document.getElementById(cadre).innerHTML = '<br /><center><img src="images/ajax-loader.gif" /></center>';	
	
	// Si la méthode est GET, on transforme l'URL (ex: mapage.php?variable1=.....)
	if(methode == 'GET' && donnees != null )
	{
		url += '?' + donnees;
		donnees = null;
	} 
	
	var XHR = null;

	// On vérifie si on est sur Firefox, Safari, Chrome ou équivalent
	if(window.XMLHttpRequest)
		XHR = new XMLHttpRequest();
	else if(window.ActiveXObject)
	
	// Ou bien sur Internet Explorer
		XHR = new ActiveXObject("Microsoft.XMLHTTP");
	
	// Sinon, l'AJAX n'est pas supporté
	else
	{
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
		return;
	}
	
	// Envoi de la requête en méthode POST
	XHR.open(methode, url, true);
	
	// Si la méthode est POST, on envoi les headers
	if(methode == 'POST')
	{
		XHR.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 	 
	}
	
	// On envoie la variable qui contient l'info que l'on veut envoyer. 
	XHR.send(donnees);

	// On guette les changements d'état de l'objet
	XHR.onreadystatechange = function attente()
	{
		// Si l'état est à 4, on affiche le résultat
		if(XHR.readyState == 4)
		{
			document.getElementById(cadre).innerHTML = XHR.responseText;
		}
	}
return;
}