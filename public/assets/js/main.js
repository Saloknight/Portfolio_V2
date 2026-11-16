window.onscroll = function() {
    const header = document.querySelector('header') ;
    
    if (window.scrollY > 1) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
};


const git = document.querySelector('.nav-git');
 const hbg = document.querySelector('.hbg');
    const header = document.querySelector('header');
    

    hbg.addEventListener('click', function (e) {
        e.preventDefault();
        hbg.classList.toggle('open');
        header.classList.toggle('open');
        git.classList.toggle('hidden');
    });