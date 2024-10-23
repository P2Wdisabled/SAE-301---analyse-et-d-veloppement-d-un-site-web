<div class="max-w-full mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">
        Mon panier ({{panier-quantity}})
    </h1>
    <div class="flex justify-center  max-lg:flex-col">
        <div class="bg-white lg:hidden p-4 rounded-lg  shadow-md  max-w-[60rem] mr-2 max-h-52">
            <div class="text-green-600 font-semibold mb-4 pb-4 border-b ">
                Disponible dès maintenant
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img alt="{{image-alt}}" class="max-w-32 min-h-20 object-cover mr-4" height="100"
                        src="{{image-url}}" />
                    <div class="">
                        <div class="text-lg font-semibold pb-4">
                            {{name}}
                        </div>

                        <div class="text-xl font-bold pb-4">
                            {{price}}
                        </div>

                        <div id="quantité1" class="text-base text-gray-400 ">
                            Qté :{{quantity-product}}
                        </div>
                        <div id="quantité2" class="flex items-center hidden mt-5 pb-4">
                            <button class="bg-gray-200 px-2  rounded">-</button>
                            <input type="text" value="{{value}}"
                                class="max-w-7 text-center border mx-3 rounded text-sm">
                            <button class="bg-gray-200 px-2  rounded">+</button>
                        </div>
                    </div>
                </div>
                <div class=" flex flex-col items-center">
                    <div class="flex items-center pt-16  relative -top-20 ">
                        <i class=" ml-4 text-gray-500 far fa-heart"></i>
                        <i class="fas fa-trash ml-4 text-blue-500 relative "></i>
                    </div>
                    <p id="modifier" class="text-blue-600 hover:underline">(Modifier)</p>
                </div>

            </div>
        </div>


        <div class="bg-white max-lg:hidden   p-4 rounded-lg shadow-md max-w-[60rem] mr-2 max-h-52">
            <div class="text-green-600 font-semibold mb-4 pb-4 border-b ">
                Disponible dès maintenant
            </div>
            <div class="flex  items-center justify-between">
                <div class="flex items-center">
                    <img alt="{{image-alt}}" class="max-w-20 min-h-20 object-cover mr-4" height="100"
                        src="{{image-url}}" />
                    <div>
                        <div class="text-lg font-semibold pb-8">
                            {{name}}
                        </div>

                        <div class="text-xl font-bold">
                            {{price}}
                        </div>
                    </div>
                </div>
                <div class="flex items-center pt-16 ">
                    <div class="flex items-center border rounded-lg mr-28">
                        <button class="px-2 py-1">
                            -
                        </button>
                        <input class="w-8 text-center border-l border-r" type="text" value="{{value-product}}" />
                        <button class="px-2 py-1">
                            +
                        </button>
                    </div>
                    <button class="ml-4 text-gray-500">
                        <i class="far fa-heart">
                        </i>
                        Ajouter à la liste de cadeaux
                    </button>
                    <button class="ml-4 text-blue-500 relative -top-20">
                        <i class="fas fa-trash">
                        </i>
                    </button>
                </div>
            </div>
        </div>





        <div class="max-w-lg max-lg:mt-4 max-lg:max-w-full">
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <div class="text-lg font-semibold mb-2">
                    Vous voulez bénéficier de la livraison GRATUITE ?
                </div>
                <div class="text-sm mb-2">
                    Dépensez {{price}} de plus.
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button class="bg-white border-2  border-black rounded-full px-4 py-2">
                        Achète plus de jouets
                    </button>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="text-lg font-semibold mb-2">
                    Récapitulatif de la commande
                </div>
                <div class="mb-6 ">
                    <button onclick="toggleMenu('attractions-menu')"
                        class="flex justify-between  flex-col w-full py-3 px-4 bg-white  focus:outline-none">
                        <div class="flex flex-row">
                            <span class="font-normal text-sm font-medium">Ajouter un code de promotion/code de
                                réduction LEGO
                                Insiders</span>
                            <span id=" attractions-arrow" class="ml-4">&#9660;</span>
                        </div>
                        <ul id="attractions-menu" class="hidden bg-white   text-lg">
                            <li class="border-b border-white">
                                <div class="flex flex-row  text-black"><input type="text" class="p-3 pr-32 border"
                                        placeholder="Saisir le code">
                                    <h6 class="bg-gray-300 text-sm p-3 flex items-center rounded-e-sm">Appliquer
                                    </h6>
                                </div>
                            </li>
                        </ul>
                    </button>

                </div>

                <div class="flex justify-between mb-2">
                    <div>
                        Montant de la commande (1 articles)
                    </div>
                    <div>
                        {{price}}
                    </div>
                </div>
                <div class="flex justify-between mb-2">
                    <div>
                        Frais de livraison
                    </div>
                    <div>
                        5,95 €
                    </div>
                </div>
                <div class="flex justify-between font-bold text-lg mb-2">
                    <div>
                        Total de la Commande
                    </div>
                    <div>
                        {{price}}
                    </div>
                </div>
                <div class="text-sm text-gray-500 mb-2">
                    TVA à incluse 3,49 €
                </div>
                <div class="flex items-center text-blue-500 bg-blue-100 p-2 rounded-lg">
                    <img src="./image/pointlego.png" alt="Point Lego" class="">
                    <div class="whitespace-nowrap">
                        +112 Points LEGO® Insiders en attente.
                    </div>
                </div>
            </div>
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md mb-6 max-lg:max-w-full max-lg:mt-4">
                <div class="flex items-center justify-center bg-orange-500 text-white p-4 rounded-full mb-4">
                    <img src="./image/bouclier.png" alt="Bouclier" class="">
                    <span class="ml-2 text-black">Paiement sécurisé</span>
                </div>
                <div class="mb-4">
                    <h2 class="text-xl font-semibold mb-2">Paiement express</h2>
                    <p class="text-gray-700 mb-4 text-sm">
                        Tous les détails de la commande seront repris directement de votre prestataire de paiement.
                        Veuillez
                        vérifier que vous avez sélectionné les bonnes adresses avant de valider votre commande.
                        Depuis le
                        prestataire de paiement, vous serez redirigé(e) vers la page de confirmation de commande. Si
                        vous avez
                        un code de réduction, veuillez choisir « Paiement sécurisé ».
                    </p>
                    <button
                        class="w-full bg-blue-600 text-white py-3 rounded-full mb-2 flex items-center justify-center">
                        <i class="fab fa-paypal mr-2"></i>
                        Payer avec <span class="font-bold ml-1">PayPal</span>
                    </button>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-full flex items-center justify-center">
                        <i class="fab fa-paypal mr-2"></i>
                        4X <span class="font-bold ml-1">PayPal</span>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700">
                        <i class="fab fa-paypal text-blue-600"></i>
                        <span class="font-bold text-blue-600">PayPal</span> Payez en 4X sans frais pour les achats
                        de 30€ à 2
                        000€.
                        <a href="#" class="text-blue-600 underline">En savoir plus</a>
                    </p>
                </div>
            </div>
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md max-lg:max-w-full">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Aide pour votre commande :</h3>
                    <div class="flex space-x-4 mb-4">
                        <a href="#" class="text-blue-600 underline">Expédition et traitement</a>
                        <a href="#" class="text-blue-600 underline">Retours</a>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Modes de paiement</h3>
                    <img src="./image/carte.png" alt="Carte" srcset="">
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5 bg-white flex flex-col p-4 ">
        <h1 class="text-3xl pl-16">Nos recommandations</h1>
        <div class="flex flex-row justify-evenly max-lg:flex-wrap ">
            <section class="w-[19rem] h-[30rem] bg-white p-4">
                <div class="bg-white p-4 border ">
                    <img src="./image/coeur.png" alt="Coeur" class="">
                    <img src="./image/76424.webp" alt="Set Lego" class="">
                </div>
                <h1 class="text-base font-bold p-2">L'Étrange Noël de Monsieur Jack -
                    Disney Tim Burton </h1>
                <div class="flex p-2 ">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/pas  etoile.jpg" alt="Star" class="max-w-5">
                </div>

                <h2 class="font-bold text-xl p-2">199.99€</h2>
                <button
                    class="text-center p-2 bg-orange-600  px-16 py-3 font-bold flex hover:bg-transparent border-orange-600 border-2 border-solid">Ajouter
                    au Panier</button>
            </section>

            <section class="w-[19rem] h-[30rem] bg-white p-4">
                <div class="bg-white p-4 border ">
                    <img src="./image/coeur.png" alt="Coeur" class="">
                    <img src="./image/76424.webp" alt="Set Lego" class="">
                </div>
                <h1 class="text-base font-bold p-2">L'Étrange Noël de Monsieur Jack -
                    Disney Tim Burton </h1>
                <div class="flex p-2 ">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/pas  etoile.jpg" alt="Star" class="max-w-5">
                </div>

                <h2 class="font-bold text-xl p-2">199.99€</h2>
                <button
                    class="text-center p-2 bg-orange-600  px-16 py-3 font-bold flex hover:bg-transparent border-orange-600 border-2 border-solid">Ajouter
                    au Panier</button>
            </section>

            <section class="w-[19rem] h-[30rem] bg-white p-4">
                <div class="bg-white p-4 border ">
                    <img src="./image/coeur.png" alt="Coeur" class="">
                    <img src="./image/76424.webp" alt="Set Lego" class="">
                </div>
                <h1 class="text-base font-bold p-2">L'Étrange Noël de Monsieur Jack -
                    Disney Tim Burton </h1>
                <div class="flex p-2 ">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/pas  etoile.jpg" alt="Star" class="max-w-5">
                </div>

                <h2 class="font-bold text-xl p-2">199.99€</h2>
                <button
                    class="text-center p-2 bg-orange-600  px-16 py-3 font-bold flex hover:bg-transparent border-orange-600 border-2 border-solid">Ajouter
                    au Panier</button>
            </section>

            <section class="w-[19rem] h-[30rem] bg-white p-4">
                <div class="bg-white p-4 border ">
                    <img src="./image/coeur.png" alt="Coeur" class="">
                    <img src="./image/76424.webp" alt="Set Lego" class="">
                </div>
                <h1 class="text-base font-bold p-2">L'Étrange Noël de Monsieur Jack -
                    Disney Tim Burton </h1>
                <div class="flex p-2 ">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/etoile.jpg" alt="Star" class="max-w-5">
                    <img src="./image/pas  etoile.jpg" alt="Star" class="max-w-5">
                </div>

                <h2 class="font-bold text-xl p-2">199.99€</h2>
                <button
                    class="text-center p-2 bg-orange-600  px-16 py-3 font-bold flex hover:bg-transparent border-orange-600 border-2 border-solid">Ajouter
                    au Panier</button>
            </section>
        </div>
    </div>
</div>
<script>
    function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        const arrow = document.querySelector(`#${menuId.replace('menu', 'arrow')}`);

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            arrow.innerHTML = '&#9650;'; // Flèche vers le haut
        } else {
            menu.classList.add('hidden');
            arrow.innerHTML = '&#9660;'; // Flèche vers le bas
        }
    }
    let quantité1 = document.getElementById('quantité1');
    let quantité2 = document.getElementById('quantité2');
    let modifier = document.getElementById('modifier');

    modifier.addEventListener('click', handler_quantité);

    function handler_quantité() {
        if (quantité1.classList.contains('hidden')) {
            quantité1.classList.remove('hidden');
            quantité2.classList.add('hidden');
            modifier.textContent = '(Modifier)';
        } else {
            quantité1.classList.add('hidden');
            quantité2.classList.remove('hidden');
            modifier.textContent = 'Terminer';
        }
    }

</script>

</body>

</html>