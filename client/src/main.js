let C = {}
C.init = function() {
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
    const userConfirmed = confirm("Voulez-vous confirmer votre commande ?");
    return userConfirmed;
}

// Fonction pour enregistrer la commande en base de données côté serveur
async function saveOrder(cartItems) {
    try {
        const response = await fetch('https://votre-domaine.com/api/save_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('userToken')}`, // Authentification par jeton si nécessaire
            },
            body: JSON.stringify({ items: cartItems })
        });

        if (!response.ok) {
            throw new Error('Une erreur est survenue lors de l\'enregistrement de la commande');
        }

        const data = await response.json();
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
            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

            // Enregistrer la commande en base de données
            const order = await saveOrder(cartItems);

            if (order) {
                // Réinitialiser le panier côté client
                resetCart();
                alert("Votre commande a été enregistrée avec succès !");
            }
        }
    } else {
        // Rediriger vers la page d'authentification
        redirectToLogin();
    }
}

// Appel de la fonction de validation du panier lorsque l'utilisateur clique sur "Valider le panier"
document.getElementById('validateCartButton').addEventListener('click', validateCart);
}

C.init();