function verification()
{
	var nom = document.formulaire.nom.value;
	var prenom = document.formulaire.prenom.value;
	var matieresEnseignees = document.formulaire.matieresEnseignees.value;
	
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
	else if(matieresEnseignees == '')
	{
		document.getElementById('erreur').innerHTML = 'Veuillez indiquer une mati&egrave;re enseign&eacute;e.';
		document.formulaire.matieresEnseignees.focus();
		return false;
	}
	else
	{
		return true;	
	}
}