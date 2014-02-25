function verification()
{
	var libelleClasse = document.formulaire.libelleClasse.value;
	
	if(libelleClasse == '')
	{
		alert('Veuillez remplir le champs.');
		return false;
	}
	else
	{
		return true;
	}
}