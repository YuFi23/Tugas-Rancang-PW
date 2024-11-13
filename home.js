const sections = document.querySelectorAll('.section');

function toggleVisibility() {
    sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        const sectionBottom = section.getBoundingClientRect().bottom;
        const windowHeight = window.innerHeight;

        if (sectionTop >= 0 && sectionBottom <= windowHeight) {
            section.classList.add('show');
        } else {
            section.classList.remove('show');
        }
    });
}

window.addEventListener('scroll', toggleVisibility);
window.addEventListener('load', toggleVisibility); 


const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('transparent');
    } else {
        navbar.classList.remove('transparent');
    }
});