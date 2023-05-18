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
