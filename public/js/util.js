document.addEventListener("DOMContentLoaded", (e) => {
    if(localStorage.getItem('bs-theme')){
        setTheme(localStorage.getItem('bs-theme'));
    }else
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setTheme('dark');
    }
  });

function setTheme(mode){
    document.querySelector('html').setAttribute('data-bs-theme',mode);
    localStorage.setItem('bs-theme',mode);
    return false;
}

class DeckUpload {
    fileUploads;
    typeSelect;
    fileCheckbox;
    constructor() { 
        this.nameString = document.getElementById('name-string').value;
        this.fileUploads = document.getElementById('file-uploads');
        this.typeSelect = document.getElementById('type');
        this.typeSelect.addEventListener("change", ()=>{ this.updateFileUpload() });
        this.fileCheckbox = document.getElementById('show-file-upload');
        this.fileCheckbox.addEventListener("change", ()=>{ this.toggleFileUpload() });
    }
    
    updateFileUpload() { 
        let selected = this.typeSelect.selectedOptions;
        if(selected.length == 1){
            let upload = '';
            let deckSize = selected[0].getAttribute('data-size');
            this.fileUploads.innerHTML = '';
            for (let i=1;i<=deckSize;i++){ 
                upload = '<div class="col-12 col-lg-6 form-group">';
                upload+= '<label for="file'+i+'">'+this.nameString+' '+i+'</label>';
                upload+= '<input class="form-control form-control-sm" type="file" id="file'+i+'" name="card_'+i+'">';
                upload+= '</div>';
                this.fileUploads.innerHTML+= upload;
            }
            
            
        }else{
            console.log('more than one selected option found for #type'); 
        }
    }

    toggleFileUpload() {
        if(this.fileCheckbox.checked){
            this.fileUploads.classList.remove('d-none');
            document.getElementById('file-upload-information').classList.add('d-none');
            document.getElementById('add-deck-form').setAttribute('enctype','multipart/form-data');
        }else{
            this.fileUploads.classList.add('d-none');
            document.getElementById('file-upload-information').classList.remove('d-none');
            document.getElementById('add-deck-form').removeAttribute('enctype');
        }
    }
}