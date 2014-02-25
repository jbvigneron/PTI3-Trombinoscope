function afficher()
{
	var classe = document.formulaire.classe.value;
	
	ajax('classe='+classe, 'ajax/etudiants.php', 'POST', 'affichage');
}

function verification()
{
	var classe = document.formulaire.classe.value;
	var nom = document.formulaire.nom.value;
	var prenom = document.formulaire.prenom.value;
	var photo = document.formulaire.photo.value;
	var jour = document.formulaire.jour.value;
	var mois = document.formulaire.mois.value;
	var annee = document.formulaire.annee.value;
	var villeOrigine = document.formulaire.villeOrigine.value;
	var email = document.formulaire.email.value;
	
	// Vérifier si tous les champs sont bien remplis
	if(nom == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer un nom.';
		document.formulaire.nom.focus();
		return false;
	}
	else if(prenom == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer un pr&eacute;nom.';
		document.formulaire.prenom.focus();
		return false;
	}
	else if(classe == '' || classe == '------')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer une classe.';
		document.formulaire.classe.focus();
		return false;
	}
	else if(jour == '' || jour == '------' || mois == '' || mois == '------' || annee == '' || annee == '------')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer une date de naissance.';
		document.formulaire.jour.focus();
		return false;
	}
	else if(photo == '' || photo == '------')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer un photo.';
		document.formulaire.photo.focus();
		return false;
	}
	else if(villeOrigine == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer une ville d\'origine.';
		document.formulaire.villeOrigine.focus();
		return false;
	}
	else if(email == '' || document.formulaire.email.value.indexOf('@') == -1 || document.formulaire.email.value.indexOf('.') == -1)
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer une adresse email valide.';
		document.formulaire.email.focus();
		return false;
	}
	else
	{
		return true;	
	}
}