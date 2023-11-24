// Ecouter le clic sur les éléments de navigation
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        // Supprimer la classe active de tous les éléments
        document.querySelectorAll('.nav-item').forEach(nav => {
            nav.classList.remove('active');
        });

        // Ajouter la classe active à l'élément cliqué
        this.classList.add('active');
    });
});

document.querySelector('.show-more').addEventListener('click', function() {
    // Faites défiler le conteneur de cartes vers le bas de 600px, par exemple
    document.querySelector('.card-container').scrollBy({
        top: 600,
        behavior: 'smooth'
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.card-checkbox');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Attendre 150ms avant de cacher la carte pour voir la coche
                setTimeout(() => {
                    this.closest('.card').style.opacity = '0';
                    setTimeout(() => this.closest('.card').style.display = 'none', 100); // puis la cache après une demi-seconde
                }, 40);
            }
        });
    });
});
