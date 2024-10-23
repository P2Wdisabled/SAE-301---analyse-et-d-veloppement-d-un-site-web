<div
    class="flex max-w-full mx-auto p-5 relative top-12  flex-row justify-between border-t border-gray-400  max-lg:flex-col ">

   <div class="max-w-40 max-h-96 pr-5 overflow-y-scroll max-lg:hidden">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-28  object-cover rounded mb-2">


    </div>

    <div class="max-w-5xl pr-5 relative">
        <img src="{{image-url}}" alt="{{image-alt}}" class="max-w-3xl rounded max-lg:max-w-96">
        <div class="absolute top-2 left-2  min-w-full flex justify-between max-h-96">
            <div class="text-gray-500 text-sm bg-white/70 px-3 py-1 rounded">3/8</div>
            <button class="bg-blue-700 text-white text-sm px-4 py-4 rounded-full flex flex-row items-center"><img
                    src="./images copy/Frame 91.png" alt="" class="  max-w-6 pr-2">Tout
                voir</button>
        </div>
    </div>




    <div class="max-w-96 max-h-96 pb-6">
        <div class="flex p-2 ">
            <img src="{{star}}" alt="Star" class="max-w-5">
            <img src="{{star}}" alt="Star" class="max-w-5">
            <img src="{{star}}" alt="Star" class="max-w-5">
            <img src="{{star}}" alt="Star" class="max-w-5">
            <img src="{{pas-star}}" alt="Star" class="max-w-5">
        </div>

       <div class="flex justify-between items-center pb-4">
            <h1 class="text-lg font-bold max-w-40 max-lg:text-base max-lg:max-w-48">La Ford Anglia™ volante</h1>
            <img src="{{image-url}}" alt="{{image-url}}" class="max-h-20 max-lg:max-h-16">
        </div>

        <div class="flex  mt-3 flex-col pb-4">
            <span class="text-2xl font-bold text-gray-700 max-lg:text-xl">{{price}}€</span>
            <span class="text-green-500 text-sm ">Disponible</span>
        </div>
        
        <div class="flex items-center mt-5 pb-4">
            <button class="bg-gray-200 px-2  rounded">-</button>
            <input type="text" value="{{value}}" class="max-w-7 text-center border mx-3 rounded text-sm">
            <button class="bg-gray-200 px-2  rounded">+</button>
            <span class="ml-3 text-sm">Limite 5</span>
        </div>
        <div class="flex items-center max-lg:justify-center mt-5 pb-4">
            <button class="bg-orange-600 px-20 text-white  py-4 rounded-full flex items-center justify-center">
                <img src="./images copy/Mask Group.png" alt="Panier" class="">
                <span class="ml-1   text-black">Ajouter au panier</span>
            </button>
            <button class="bg-gray-200 p-2 rounded-full ml-3 flex items-center justify-center">
                <img src="./images copy/Button - Ajouter à la liste de cadeaux.png" alt="Coeur" class="bg-transparent">
            </button>
        </div>
        <div class="flex items-center bg-cyan-100 p-3 rounded mt-5 pb-4">
            <img src="./images copy/General-Sale-202209-PDP-Promo-Badge.png" alt="Shopping Cart Icon"
                class="max-h-16  max-w-16">
            <div class="ml-3">
                <p class="text-sm">Parcours dès maintenant les ensembles LEGO® en promotion</p>
                <a href="#" class="text-blue-500 text-sm">Visiter la boutique</a>
            </div>
        </div>
        <div class="flex items-center mt-5 pb-4">
            <p class="ml-3 text-sm">Attention ! Petits éléments.</p>
            <img src="./images copy/407960-interdit_0-3-ans-d8c14d-large-1637308206.jpg" alt="Warning Icon"
                class="max-w-14 max-h-14">
        </div>
        <div class="max-w-md mx-auto mt-5">
            <div class="flex items-center justify-between border-b py-4 cursor-pointer" id="checkStockButton">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                    <span class="ml-4 text-lg">Vérifier le stock du magasin</span>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>

            <div class="max-w-md mx-auto mt-5">
                <div class="flex items-center justify-between border-b py-4 cursor-pointer" id="livraisonButton">
                    <div class="flex items-center">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                        <span class="ml-4 text-lg">Livraisons et retours</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>








    <!-- Contenu de livraisons et retours -->
    <div id="livraisonContent" class="hidden bg-white p-6 absolute top-20 left-3/4 max-w-96 rounded-lg shadow mt-4">
        <button id="closeButton" class=" top-3 right-3 text-gray-600 hover:text-gray-800">
            <h2 class="text-2xl font-bold mb-4">Livraisons et retours</h2>
            <h3 class="text-xl font-semibold mb-2">Options de livraison</h3>
            <p class="mb-4">Les commandes passées avant 15 h 30 CET seront traitées le jour même. Le choix des
                options de livraison se fait lors du paiement.</p>
            <h4 class="text-lg font-semibold mb-2">Livraison standard</h4>
            <ul class="list-disc list-inside mb-4">
                <li>De 2 à 5 jours ouvrés.</li>
                <li>5,95€.</li>
                <li>Livraison gratuite pour tout achat de plus de 55€.</li>
            </ul>
            <h4 class="text-lg font-semibold mb-2">Livraison en point de collecte</h4>
            <ul class="list-disc list-inside mb-4">
                <li>De 2 à 5 jours ouvrés.</li>
                <li>5,95€.</li>
                <li>Livraison gratuite pour tout achat de plus de 55€.</li>
            </ul>
            <h4 class="text-lg font-semibold mb-2">Livraison express</h4>
            <ul class="list-disc list-inside mb-4">
                <li>De 1 à 3 jours ouvrés.</li>
                <li>29€.</li>
            </ul>
            <h4 class="text-lg font-semibold mb-2">Annulation de commande</h4>
            <p class="mb-4">Vous pourrez annuler
    </div>




    <div id="stockPanel"
        class="hidden bg-white p-6 absolute top-20 left-3/4 max-w-96 min-h-full rounded-lg shadow mt-8">
        <button id="closeButton1" class=" top-3 right-3 text-gray-600 hover:text-gray-800">
            <h2 class="text-2xl font-bold mb-4">Vérifier le stock du magasin</h2>

            <p class="mb-4">Enter your adress to find a LEGO Store near you . Please call to ensure item availabilty
                and inquire about the possiblity for curbside pick-up</p>

            <div class="flex flex-row justify-center   text-black">
                <input type="text" class="border px-12 " placeholder="Indiquer le code postal">
                <img src="../produit/images copy/Button menu - Search.png" alt="Chevron"
                    class=" rounded-e-lg px-2  border border-blue-600 ">
            </div>
    </div>

</div>
<div class="flex justify-center relative max-lg:mt-5  lg:flex-wrap ">
    <ul class="flex flex-row  justify-between items-center max-lg:flex-wrap max-lg:relative max-lg:top-96">
        <li class="border-r border-gray-400 text-center p-3 flex flex-col lg:flex-wrap items-center">
            <img src="./images copy/Img - Âges_mask-group.png" alt="" class="min-w-11">
            <h4 class="font-medium text-3xl">{{age}}</h4>
            <h5 class="text-xl">Âges</h5>
        </li>
        <li class="border-r border-gray-400 text-center p-3 flex flex-col items-center">
            <img src="./images copy/Img - Nombre de pièces_mask-group.png" alt="" class="min-w-11">
            <h4 class="font-medium text-3xl">{{}}pieces</h4>
            <h5 class="text-xl">Nombres de pièces</h5>
        </li>
        <li class="border-r border-gray-400 text-center p-3 flex flex-col items-center">
            <img src="./images copy/SVG - Insiders.png" alt="" class="">
            <h4 class="font-medium text-3xl">{{point}}</h4>
            <h5 class="text-xl">Point LEGO Insiders</h5>
        </li>
        <li class=" text-center p-3 flex flex-col items-center">
            <img src="./images copy/SVG.png" alt="" class="">
            <h4 class="font-medium text-3xl">{{ref}}</h4>
            <h5 class="text-xl">réference</h5>
        </li>
    </ul>
</div>