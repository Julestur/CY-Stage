const iconChangementPhotoProfil     = document.getElementById('icon-changement-photo-profil');
const inputChangementPhotoProfil    = document.getElementById('input-changement-photo-profil');

inputChangementPhotoProfil.addEventListener('change', function()
{
    iconChangementPhotoProfil.name = "checkmark-outline";
})