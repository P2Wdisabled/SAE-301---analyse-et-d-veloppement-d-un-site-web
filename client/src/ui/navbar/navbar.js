import { genericRenderer } from "../../lib/utils.js"; 
let NavbarLoad = {

    enable: function(){
        // Fermer le menu lorsqu'on clique en dehors (uniquement pour mobile)
        window.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const boutiqueMenu = document.getElementById('boutiqueMenu');
            const isClickInsideMenu = mobileMenu.contains(event.target) || boutiqueMenu.contains(event.target);
            const isMenuButton = event.target.closest('.fa-bars') || event.target.closest('.fa-times') || event.target.closest('.fa-chevron-right') || event.target.closest('.fa-arrow-left');

            if (!isClickInsideMenu && !isMenuButton) {
                mobileMenu.classList.add('hidden');
                boutiqueMenu.classList.add('hidden');
            }
        });
        let menu = document.getElementById('menu');
        let boutique = document.getElementById('boutique');
        menu.addEventListener('click', this.toggleMenu)
        boutique.addEventListener('click', this.toggleBoutique)
    },

    toggleMenu: function(){
        // Ouvrir et fermer le menu lorsqu'on clique sur le bouton menu
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    },
    toggleBoutique: function(){
        // Ouvrir et fermer le menu boutique lorsqu'on clique sur le bouton menu
        const boutiqueMenu = document.getElementById('boutiqueMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        boutiqueMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('hidden');
    }
//boutique - menu
}

export {NavbarLoad};
