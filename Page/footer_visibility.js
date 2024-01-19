// footerVisibility.js

document.addEventListener('DOMContentLoaded', function() {
    var footer = document.querySelector('.footer');

    function toggleFooterVisibility() {
        // Vérifiez si l'utilisateur a atteint le bas de la page
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            footer.style.display = 'block'; // Afficher le footer
        } else {
            footer.style.display = 'none'; // Masquer le footer
        }
    }

    // Attachez l'événement de défilement à la fenêtre
    window.addEventListener('scroll', toggleFooterVisibility);

    // Appelez la fonction pour vérifier l'état initial
    toggleFooterVisibility();
});
