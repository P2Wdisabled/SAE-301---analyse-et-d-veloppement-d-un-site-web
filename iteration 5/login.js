// Gestion du formulaire de connexion
let loginForm = document.querySelector("#loginForm");
loginForm.addEventListener("submit", function(event) {
    event.preventDefault();

    let username = document.querySelector("#username").value;
    let password = document.querySelector("#password").value;

    // Vérifier si l'utilisateur existe
    let users = JSON.parse(localStorage.getItem("users")) || [];
    let user = users.find(u => u.username === username && u.password === password);

    if (user) {
        // Authentification réussie
        localStorage.setItem("isAuthenticated", "true");
        alert("Connexion réussie !");
        // Rediriger vers le panier pour confirmer la commande
        window.location.href = "cart.html";
    } else {
        alert("Nom d'utilisateur ou mot de passe incorrect.");
    }
});
