const sections = document.querySelectorAll('.section');
function revealOnScroll() {
    sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        const triggerPoint = window.innerHeight * 0.8;
        if (sectionTop < triggerPoint) {
            section.classList.add('show');
        } else {
            section.classList.remove('show');
        }
    });
}
window.addEventListener('scroll', revealOnScroll);

const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
if (window.scrollY > 50) { // Saat scroll lebih dari 50px
    navbar.classList.add('transparent');
} else {
    navbar.classList.remove('transparent');
}
});