// Gestion du formulaire d'inscription
let registerForm = document.querySelector("#registerForm");
registerForm.addEventListener("submit", function(event) {
    event.preventDefault();

    let username = document.querySelector("#username").value;
    let password = document.querySelector("#password").value;

    // Vérifier si l'utilisateur existe déjà
    let users = JSON.parse(localStorage.getItem("users")) || [];
    let existingUser = users.find(u => u.username === username);

    if (existingUser) {
        alert("Ce nom d'utilisateur est déjà pris.");
    } else {
        // Ajouter le nouvel utilisateur
        users.push({
            username: username,
            password: password
        });
        localStorage.setItem("users", JSON.stringify(users));
        alert("Inscription réussie ! Vous pouvez maintenant vous connecter.");
        window.location.href = "login.html";
    }
});
