function verification()
{
	var nom = document.formulaire.nom.value;
	var image = document.formulaire.image.value;
	var site = document.formulaire.site.value;
	var president = document.formulaire.president.value;
	var vicePresident = document.formulaire.vicePresident.value;
	var secretaire = document.formulaire.secretaire.value;
	var tresorier = document.formulaire.tresorier.value;
	
	// Vérifier si tous les champs sont bien remplis
	if(nom == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer un nom.';
		document.formulaire.nom.focus();
		return false;
	}
	else if(image == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez s&eacute;lectionner une image.';
		document.formulaire.image.focus();
		return false;
	}
	else if(site == '' || document.formulaire.site.value.indexOf('http://') == -1)
	{
		document.getElementById('erreur').innerHTML = 'Veuillez entrer une adresse valide. (http://)';
		document.formulaire.site.focus();
		return false;
	}
	else if(president == '' || vicePresident == '' || secretaire == '' || tresorier == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez s&eacute;lectionner tous les membres du bureau.';
		return false;
	}
	else
	{
		return true;	
	}
}