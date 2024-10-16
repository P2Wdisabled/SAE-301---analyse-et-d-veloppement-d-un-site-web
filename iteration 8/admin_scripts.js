// admin_scripts.js

// Fonction pour gérer la connexion administrateur
async function adminLogin(username, password) {
    try {
        let formData = new FormData();
        formData.append('username', username);
        formData.append('password', password);

        let response = await fetch('admin_requester.php?action=adminLogin', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            alert(result.success);
            window.location.href = 'admin_dashboard.html';
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Erreur lors de la connexion administrateur :', error);
    }
}

// Fonction pour vérifier si l'administrateur est connecté
async function isAdminAuthenticated() {
    try {
        let response = await fetch('check_admin_auth.php');
        let result = await response.json();
        return result.isAdminAuthenticated;
    } catch (error) {
        console.error('Erreur lors de la vérification de l\'authentification administrateur :', error);
        return false;
    }
}

// Fonction pour récupérer toutes les commandes
async function fetchAllOrders() {
    try {
        let response = await fetch('admin_requester.php?action=getAllOrders');
        let orders = await response.json();
        return orders;
    } catch (error) {
        console.error('Erreur lors de la récupération des commandes :', error);
    }
}

// Fonction pour afficher les commandes
function renderOrders(orders) {
    let container = document.querySelector("#ordersContainer");
    container.innerHTML = '';

    if (orders.error) {
        container.textContent = orders.error;
        return;
    }

    if (orders.length === 0) {
        container.textContent = "Aucune commande disponible.";
        return;
    }

    let table = document.createElement("table");
    let thead = document.createElement("thead");
    let headerRow = document.createElement("tr");

    ["ID", "Utilisateur", "Date", "Statut", "Actions"].forEach(text => {
        let th = document.createElement("th");
        th.textContent = text;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    let tbody = document.createElement("tbody");

    orders.forEach(order => {
        let row = document.createElement("tr");

        // ID
        let tdId = document.createElement("td");
        tdId.textContent = order.id;
        row.appendChild(tdId);

        // Utilisateur
        let tdUser = document.createElement("td");
        tdUser.textContent = order.username;
        row.appendChild(tdUser);

        // Date
        let tdDate = document.createElement("td");
        tdDate.textContent = order.date;
        row.appendChild(tdDate);

        // Statut
        let tdStatus = document.createElement("td");
        let statusSelect = document.createElement("select");
        ["en cours", "disponible", "annulée", "retirée"].forEach(status => {
            let option = document.createElement("option");
            option.value = status;
            option.textContent = status;
            if (order.status === status) {
                option.selected = true;
            }
            statusSelect.appendChild(option);
        });

        statusSelect.addEventListener("change", function() {
            updateOrderStatus(order.id, this.value);
        });

        tdStatus.appendChild(statusSelect);
        row.appendChild(tdStatus);

        // Actions
        let tdActions = document.createElement("td");
        let viewDetailsButton = document.createElement("button");
        viewDetailsButton.textContent = "Voir les détails";
        viewDetailsButton.addEventListener("click", function() {
            window.location.href = `order_details.html?orderId=${order.id}`;
        });
        tdActions.appendChild(viewDetailsButton);
        row.appendChild(tdActions);

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    container.appendChild(table);
}

// Fonction pour mettre à jour le statut d'une commande
async function updateOrderStatus(orderId, status) {
    try {
        let formData = new FormData();
        formData.append('orderId', orderId);
        formData.append('status', status);

        let response = await fetch('admin_requester.php?action=updateOrderStatus', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            alert(result.success);
        } else {
            alert(result.error);
            // Recharger les commandes
            loadOrders();
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du statut de la commande :', error);
    }
}

// Fonction pour charger les commandes
async function loadOrders() {
    let isAuthenticated = await isAdminAuthenticated();
    if (!isAuthenticated) {
        window.location.href = 'admin_login.html';
        return;
    }

    let orders = await fetchAllOrders();
    renderOrders(orders);
}

// Gestion du formulaire de connexion administrateur
if (document.querySelector("#adminLoginForm")) {
    document.querySelector("#adminLoginForm").addEventListener("submit", function(event) {
        event.preventDefault();
        let username = document.querySelector("#username").value;
        let password = document.querySelector("#password").value;
        adminLogin(username, password);
    });
}

// Chargement initial des commandes
if (document.querySelector("#ordersContainer")) {
    document.addEventListener('DOMContentLoaded', loadOrders);
}

// Fonction pour récupérer les détails d'une commande
async function fetchOrderDetails(orderId) {
    try {
        let response = await fetch(`admin_requester.php?action=getOrderDetails&orderId=${orderId}`);
        let order = await response.json();
        return order;
    } catch (error) {
        console.error('Erreur lors de la récupération des détails de la commande :', error);
    }
}

// Fonction pour afficher les détails de la commande
function renderOrderDetails(order) {
    let container = document.querySelector("#orderDetailsContainer");
    container.innerHTML = '';

    if (order.error) {
        container.textContent = order.error;
        return;
    }

    let h2 = document.createElement("h2");
    h2.textContent = `Commande #${order.id} - Utilisateur : ${order.username}`;
    container.appendChild(h2);

    let pDate = document.createElement("p");
    pDate.textContent = `Date : ${order.date}`;
    container.appendChild(pDate);

    let pStatus = document.createElement("p");
    pStatus.textContent = `Statut : ${order.status}`;
    container.appendChild(pStatus);

    let table = document.createElement("table");
    let thead = document.createElement("thead");
    let headerRow = document.createElement("tr");

    ["Produit", "Options", "Quantité", "Actions"].forEach(text => {
        let th = document.createElement("th");
        th.textContent = text;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    let tbody = document.createElement("tbody");

    order.items.forEach(item => {
        let row = document.createElement("tr");

        // Produit
        let tdProduct = document.createElement("td");
        tdProduct.textContent = item.name;
        row.appendChild(tdProduct);

        // Options
        let tdOptions = document.createElement("td");
        if (item.options && item.options !== '{}') {
            let optionsObj = JSON.parse(item.options);
            tdOptions.textContent = Object.entries(optionsObj).map(([key, value]) => `${key}: ${value}`).join(", ");
        } else {
            tdOptions.textContent = "N/A";
        }
        row.appendChild(tdOptions);

        // Quantité
        let tdQuantity = document.createElement("td");
        let quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.value = item.quantity;
        quantityInput.min = 1;
        quantityInput.addEventListener("change", function() {
            updateOrderItem(item.id, parseInt(this.value));
        });
        tdQuantity.appendChild(quantityInput);
        row.appendChild(tdQuantity);

        // Actions
        let tdActions = document.createElement("td");
        // Vous pouvez ajouter d'autres actions si nécessaire
        row.appendChild(tdActions);

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    container.appendChild(table);
}

// Fonction pour mettre à jour la quantité d'un article de commande
async function updateOrderItem(orderItemId, quantity) {
    try {
        let formData = new FormData();
        formData.append('orderItemId', orderItemId);
        formData.append('quantity', quantity);

        let response = await fetch('admin_requester.php?action=updateOrderItem', {
            method: 'POST',
            body: formData
        });
        let result = await response.json();

        if (result.success) {
            alert(result.success);
            // Recharger les détails de la commande
            displayOrderDetails();
        } else {
            alert(result.error);
            // Recharger les détails de la commande
            displayOrderDetails();
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour de l\'article de commande :', error);
    }
}

// Fonction pour afficher les détails de la commande
async function displayOrderDetails() {
    let params = new URLSearchParams(window.location.search);
    let orderId = params.get('orderId');

    if (orderId) {
        let order = await fetchOrderDetails(orderId);
        renderOrderDetails(order);
    } else {
        document.querySelector("#orderDetailsContainer").textContent = "ID de la commande manquant dans l'URL.";
    }
}

// Chargement initial des détails de la commande
if (document.querySelector("#orderDetailsContainer")) {
    document.addEventListener('DOMContentLoaded', displayOrderDetails);
}
