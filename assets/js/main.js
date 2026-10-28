 
const page = document.title
const headers = document.querySelector('header')


const liens = headers.querySelectorAll('nav a');


// Fait disparaitre le lien de la page ou on se trouve
liens.forEach(lien => {
    if (lien.innerText.trim() === page) {
        lien.classList.add('disapear'); 
    }
});






 /*Ecoute si le hbg est cliqué et applique la class open au hbg */
 
 const hbg = document.querySelector('.hbg');
    const header = document.querySelector('header');

    hbg.addEventListener('click', function (e) {
        e.preventDefault();
        header.classList.toggle('open');
    });



 /*Applique la class scrolled au header */
window.onscroll = function() {
    const header = document.querySelector('header') ;
    
    if (window.scrollY > 120) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
};