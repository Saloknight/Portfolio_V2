window.onscroll = function() {
    const header = document.querySelector('header') ;
    
    if (window.scrollY > 1) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
};