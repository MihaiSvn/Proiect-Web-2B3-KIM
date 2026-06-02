document.addEventListener('DOMContentLoaded', ()=>{
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileNav = document.getElementById('mobile-nav');

    if(hamburgerBtn && mobileNav){
        hamburgerBtn.addEventListener('click', () =>{
            mobileNav.classList.toggle('is-open');

            const icon = hamburgerBtn.querySelector('i'); //iau iconita
            if(mobileNav.classList.contains('is-open')){
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        })
    }
});