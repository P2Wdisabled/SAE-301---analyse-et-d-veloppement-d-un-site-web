import { genericRenderer } from "../../lib/utils.js"; 
let FooterLoad = {

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
        let footerMenus = document.querySelectorAll('#footerMenu')
    footerMenus.forEach(menu => {
        menu.addEventListener('click', () => {
            this.footerMenu(menu.dataset.name)
        })
    })
    },

    footerMenu: function(name) {
        const menu = document.getElementById(name);
        const arrowId = name.replace('menu', 'arrow');
        const arrow = document.getElementById(arrowId);
    
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            if (arrow) {
                arrow.innerHTML = '&#9650;'; // Flèche vers le haut
            }
        } else {
            menu.classList.add('hidden');
            if (arrow) {
                arrow.innerHTML = '&#9660;'; // Flèche vers le bas
            }
        }
    }
//boutique - menu
}

export {FooterLoad};
