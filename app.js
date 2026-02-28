const tagsSorties           = document.getElementsByClassName("tag-sortie");
const tagsSortiesSuppr      = document.getElementsByClassName("tag-sortie-suppr");

for (let tagSortie of tagsSorties) {
    tagSortie.addEventListener("mouseup", function (e) {
        if (e.target.closest('.tag-sortie-suppr')) return;

        if (e.button == 2) { // clic droit
            e.preventDefault();
            tagSortie.classList.add('supprimable');
        }
        else if (e.button == 0)
        {
            let fichier = tagSortie.getElementsByTagName('div')[0].dataset.lien;
            window.location = `./tableau.php?sortie=${encodeURIComponent(fichier)}`;
        }
    });

    window.addEventListener("mouseup", function (e) {
        if (e.target.closest('.tag-sortie-suppr')) return;

        if (!tagSortie.contains(e.target)) {
            tagSortie.classList.remove('supprimable');
        }
    });

    tagSortie.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    });
}


// Suppression long press
for(let tagSortie of tagsSorties)
{
    let pressTimer;
    let supprimable = false;

    // si on appuis autre part, plus rien n'est supprimable
    document.addEventListener('touchstart', (e) => {
        if (e.target.closest('.tag-sortie-suppr')) return;

        for(let tag of tagsSorties) tag.classList.remove('supprimable');

        supprimable = false;
    });

    // long press
    tagSortie.addEventListener('touchstart', (e) => {
        pressTimer = setTimeout(() => {
            // activer supprimable sur tous les tags
            for(let tag of tagsSorties)
            {
                tag.classList.add('supprimable');

                // pour plus de style, on ajoute un delai different a chaque tag 
                // delais Apple
                const delais = [
                    0,
                    0.05,
                    0.1,
                    0.15,
                    0.2
                ]
                const delai = delais[Math.floor(Math.random() * 5) + 1];
                tag.style.animationDelay = `${delai}s`;
            }
            supprimable = true;
        }, 500);
    });

    // si on appuis pas assez longtemps, les tags ne deviennent pas supprimables
    tagSortie.addEventListener('touchend', (e) => {
        if (e.target.closest('.tag-sortie-suppr')) return;

        if(!supprimable)
        {
            clearTimeout(pressTimer);
            for(let tag of tagsSorties) tag.classList.remove('supprimable');

            let fichier = tagSortie.getElementsByTagName('div')[0].dataset.lien;
            window.location = `./tableau.php?sortie=${encodeURIComponent(fichier)}`;
        }
    });

    tagSortie.addEventListener('touchcancel', (e) => {
        if (e.target.closest('.tag-sortie-suppr')) return;
        
        if(!supprimable)
        {
            clearTimeout(pressTimer);
            for(let tag of tagsSorties) tag.classList.remove('supprimable');
        }
    });
}

// Suppression des tags
for(let tagSortieSuppr of tagsSortiesSuppr)
{
    tagSortieSuppr.addEventListener('click', function()
    {
        // Attention l'app doit avoir au moins une sortie alors on verifi
        let tagSortie       = tagSortieSuppr.parentElement;
        let listeTagSortie  = tagSortie.parentElement;
        let aUneSeuleSortie = listeTagSortie.getElementsByTagName('li').length == 1;

        if(aUneSeuleSortie) return;

        // on recupere le nom du fichier, stocké dans data-lien sur le html
        let fichier = tagSortie.getElementsByTagName('div')[0].dataset.lien;

        // suppression du fichier avec php + suppression sur la page (sans reload, donc plus smooth)
        window.location = `suppSortie.php?sortie=${encodeURIComponent(fichier)}`;
        tagSortie.parentElement.removeChild(tagSortie);
    });
}