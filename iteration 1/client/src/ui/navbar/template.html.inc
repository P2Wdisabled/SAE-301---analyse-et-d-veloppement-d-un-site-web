    <!-- Mobile Navbar (Visible uniquement sur mobile) -->
    <header class="sm:flex items-center justify-between p-4 border-b-4 border-yellow-500 md:hidden fixed top-0 left-0 right-0 bg-white z-40">
        <div class="flex items-center">
            <i class="fas fa-bars text-xl cursor-pointer" id="menu"></i>
            <span class="ml-2 text-xs">MENU</span>
        </div>
        <img alt="LEGO logo" class="h-12" src="https://storage.googleapis.com/a1aa/image/KWDE3C0d2mo4BxSeLklG40paxAxiQLjSe6fPRFEEXQaSYhSnA.jpg" width="50"/>
        <div class="flex items-center space-x-4">
            <i class="fas fa-search text-xl cursor-pointer"></i>
            <i class="fas fa-user text-xl cursor-pointer"></i>
            <i class="fas fa-heart text-xl cursor-pointer"></i>
            <div class="relative">
                <i class="fas fa-shopping-bag text-xl cursor-pointer"></i>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
            </div>
        </div>
    </header>

    <!-- Desktop Navbar (Visible à partir de md) -->
    <header class="sm:hidden md:flex items-center justify-between p-4 border-b-4 border-yellow-500 desktop-header fixed top-0 left-0 right-0 bg-white">
        <div class="flex items-center space-x-8">
            <img alt="LEGO logo" class="h-12" src="https://storage.googleapis.com/a1aa/image/KWDE3C0d2mo4BxSeLklG40paxAxiQLjSe6fPRFEEXQaSYhSnA.jpg" width="50"/>
            <nav class="flex items-center space-x-8">
                <a href="#" class="text-black font-bold hover:underline">BOUTIQUE</a>
                <a href="#" class="text-black hover:underline">DÉCOUVRIR</a>
                <a href="#" class="text-black hover:underline">AIDE</a>
                <a href="#" class="text-red-600 border border-red-600 px-4 py-2 rounded hover:bg-red-600 hover:text-white">NOËL</a>
            </nav>
        </div>
        <div class="flex items-center space-x-6">
            <i class="fas fa-search text-xl cursor-pointer"></i>
            <i class="fas fa-user text-xl cursor-pointer"></i>
            <i class="fas fa-heart text-xl cursor-pointer"></i>
            <div class="relative">
                <i class="fas fa-shopping-bag text-xl cursor-pointer"></i>
                <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Sidebar -->
    <div id="mobileMenu" class="fixed inset-0 bg-white z-50 flex flex-col hidden p-4 md:hidden overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <span class="text-lg font-semibold">MENU</span>
            <i class="fas fa-times text-xl cursor-pointer" id="menu"></i>
        </div>
        <div class="p-4">
            <ul class="space-y-4">
                <li>
                    <a href="#" class="text-red-600 block">NOËL</a>
                </li>
                <li>
                    <a href="#" class="block">ACCUEIL</a>
                </li>
                <li class="flex justify-between items-center cursor-pointer" id="boutique">
                    <span>BOUTIQUE</span>
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="flex justify-between items-center">
                    <span>DÉCOUVRIR</span>
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="flex justify-between items-center">
                    <span>AIDE</span>
                    <i class="fas fa-chevron-right"></i>
                </li>
            </ul>
        </div>
        <div class="p-4 border-t border-gray-300">
            <ul class="space-y-4">
                <li class="flex items-center">
                    <i class="fas fa-user-circle text-yellow-500 mr-2"></i>
                    <span>Compte</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-gift text-blue-500 mr-2"></i>
                    <span>Rejoins LEGO® Insiders</span>
                </li>
                <li>Ma Liste de cadeaux</li>
                <li>Mon panier (0)</li>
                <li class="flex items-center">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>France</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Boutique Sidebar -->
    <div id="boutiqueMenu" class="fixed inset-0 bg-white z-50 hidden p-4 md:hidden overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-300">
            <div class="flex items-center">
                <i class="fas fa-arrow-left text-lg cursor-pointer" id="boutique"></i>
                <span class="ml-2 text-lg font-semibold">BOUTIQUE</span>
            </div>
            <i class="fas fa-times text-lg cursor-pointer" id="boutique"></i>
        </div>
        <div class="p-4">
            <ul class="space-y-2">
                <li class="py-2 border-b border-gray-300">Architecture</li>
                <li class="py-2 border-b border-gray-300">Batman™</li>
                <li class="py-2 border-b border-gray-300">Collection Botanique</li>
                <li class="py-2 border-b border-gray-300">BrickHeadz</li>
                <li class="py-2 border-b border-gray-300">City</li>
                <li class="py-2 border-b border-gray-300">Classic</li>
                <li class="py-2 border-b border-gray-300">Creator 3in1</li>
                <li class="py-2 border-b border-gray-300">Creator Expert</li>
                <li class="py-2 border-b border-gray-300">DC</li>
                <li class="py-2 border-b border-gray-300">Disney</li>
            </ul>
        </div>
    </div>