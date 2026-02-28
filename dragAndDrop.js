let inputHidden     = document.getElementById('input-hidden');
let inputFile       = document.getElementById('input-file');
let dropZone        = document.getElementById('drop-zone');
let icon            = document.getElementById('icon-file-drop');
let infoDropzone    = document.getElementById('info-drop-zone');
let boutonEnvoi     = document.getElementById('bouton-envoi');

let filesData = [];
let newNames = [];

function gererFichier(files)
{
    if (files.length > 0) {

        for (let file of files) {
            // Vérifie si c’est un CSV
            if (!file.name.toLowerCase().endsWith('.csv')) {
                icon.classList.add('error-shake');
                setTimeout(() => {
                    icon.classList.remove('error-shake');
                }, 300);
                return;
            }

            newNames.push(file.name); // ← On stocke d’abord le nom

            let reader = new FileReader();
            reader.onload = function(e) {
                filesData.push({ name: file.name, content: e.target.result });
                inputHidden.value = JSON.stringify(filesData);
                updateIcon();
            };
            reader.readAsText(file);
        }

        if(newNames.length != 0)
        {
            infoDropzone.innerHTML = "";
        }

        // Mise à jour de tous les noms en une fois (après la boucle)
        infoDropzone.innerHTML += newNames.join('<br>');
    }
}


function autoriserDrop(evt)
{
    evt.preventDefault();
}

function onDrag(evt)
{
    dropZone.classList.add('drag-hover');

    
    const rect = dropZone.getBoundingClientRect();
    const offsetX = evt.clientX - rect.left;
    const offsetY = evt.clientY - rect.top;
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const dx = offsetX - centerX;
    const dy = offsetY - centerY;

    icon.style.transform = `translate(${dx / 10}px, ${dy / 10}px) scale(1.3)`;
    //icon.style.transition =  '0.1s';
}

function endDrag(evt)
{
    dropZone.classList.remove('drag-hover');

    icon.style.transform = '';
    icon.style.transition =  '0.0s';
}

function onClick(evt)
{
    inputFile.click();
}

function updateIcon()
{
    icon.name = "document-attach-outline";

    boutonEnvoi.classList.add('enabled');
    boutonEnvoi.disabled = false;
}

function onDrop(evt) {
    evt.preventDefault();
    endDrag();

    gererFichier(evt.dataTransfer.files);
}

function onInputFileChange(evt)
{
    evt.preventDefault();

    gererFichier(evt.target.files);

    /** On supprime ce qu'il y a dans le input file car le contenu du fichier sera dans le hidden input */
    inputFile.value = "";
}

dropZone.addEventListener('click', onClick);
dropZone.addEventListener('drop', onDrop);
dropZone.addEventListener('dragover', autoriserDrop);
dropZone.addEventListener('dragover', onDrag);
dropZone.addEventListener('dragleave', endDrag);

inputFile.addEventListener('change', onInputFileChange);



