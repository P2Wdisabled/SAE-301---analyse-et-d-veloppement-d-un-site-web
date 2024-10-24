


// L’application dispose d’un back office administrateur avec un accès dédié et authentifié. 
//Cet accès permet de consulter la liste des commandes (triées par date) et de modifier leur statut. Le statut d’une commande est soit 
//“en cours” (commande juste validée)  soit “disponible”, soit “annulée”, soit “retirée”. 


//Note : 
// Une commande annulée par l’administrateur entraîne une mise à jour du stock. 
// Une commande annulée, si on la repasse sous un autre statut, entraîne aussi une mise à jour du stock.
// case date case etat

 let commandes = [
        { id: 1, date: '2024-10-20', statut: 'en cours' },
        { id: 2, date: '2024-10-21', statut: 'disponible' },
        { id: 3, date: '2024-10-22', statut: 'annulée' },
        { id: 4, date: '2024-10-19', statut: 'annulée' }
    ];



let C = {}
C.init = async () => {
    function changerStatutCommande(id, nouveaustatut) {
        // Chercher la commande par ID
        let commande = commandes.find(commande => commande.id === id);
        if (commande) {
            let statutsValides = ['en cours', 'disponible', 'annulée', 'retirée'];
            if (statutsValides.includes(nouveaustatut)) {
                commande.statut = nouveaustatut;
                console.log(`Le statut de la commande ${id} a été changé à "${nouveaustatut}".`);
            } 
        else {
            console.log(`Commande avec ID ${id} non trouvée.`);
        }
    }
}
    changerStatutCommande(2, 'retirée');
    

    function afficherCommandes() {
        let tbody = document.getElementById('tbody');
        tbody.innerHTML = ''; // Clear previous content
        let ul = document.createElement('ul');

        for (let commande of commandes) {
            let li = document.createElement('li');
            li.textContent = `Commande ${commande.id} : ${commande.statut} (${commande.date})`;
           ul.appendChild(li);
        }

        tbody.appendChild(ul);
    }  
     afficherCommandes();
}
C.init();