let C = {}
C.init = function() {
    // Vérifier si l'utilisateur est connecté
// Vérifier si l'utilisateur est connecté
function isUserLoggedIn() {
    // Supposons que vous stockiez un jeton dans le localStorage après connexion
    return !!localStorage.getItem('userToken');
}

// Fonction pour rediriger vers la page de connexion
function redirectToLogin() {
    window.location.href = '/login.php'; // URL de votre page d'authentification en PHP
}

// Fonction pour confirmer la validation du panier
function confirmOrder() {
    let userConfirmed = confirm("Voulez-vous confirmer votre commande ?");
    return userConfirmed;
}

// Fonction pour enregistrer la commande en base de données côté serveur
async function saveOrder(cartItems, totalAmount) {
    try {
        let response = await fetch('https://votre-domaine.com/api/save_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('userToken')}`, // Authentification par jeton si nécessaire
            },
            body: JSON.stringify({
                items: cartItems,
                user_id: localStorage.getItem('userId'), // ID utilisateur stocké après connexion
                total_amount: totalAmount
            })
        });

        if (!response.ok) {
            throw new Error('Une erreur est survenue lors de l\'enregistrement de la commande');
        }

        let data = await response.json();
        return data; // Renvoie la réponse de l'API (par exemple, ID de la commande)
    } catch (error) {
        console.error('Erreur:', error);
        alert("Une erreur est survenue lors de la validation de la commande. Veuillez réessayer.");
        return null;
    }
}

// Fonction pour réinitialiser le panier
function resetCart() {
    // Supposons que vous stockiez le panier dans le localStorage
    localStorage.removeItem('cartItems');
    alert("Votre panier a été réinitialisé.");
}

// Fonction principale pour valider le panier
async function validateCart() {
    if (isUserLoggedIn()) {
        // L'utilisateur est connecté
        if (confirmOrder()) {
            // Récupérer les articles du panier
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

            // Calculer le montant total du panier
            let totalAmount = cartItems.reduce((total, item) => total + item.unit_price * item.quantity, 0);

            // Enregistrer la commande en base de données
            let order = await saveOrder(cartItems, totalAmount);

            if (order && order.success) {
                // Réinitialiser le panier côté client
                resetCart();
                alert("Votre commande a été enregistrée avec succès !");
            }
        }
    } else {
        // Rediriger vers la page d'authentification
        redirectToLogin();
    }
    // Appel de la fonction de validation du panier lorsque l'utilisateur clique sur "Valider le panier"
    document.getElementById('validateCartButton').addEventListener('click', validateCart);
}

}
C.init();