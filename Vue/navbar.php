<!-- Navbar principale -->
<div class="flex flex-col fixed z-10 left-0 transition-transform -translate-x-full sm:translate-x-0 items-center w-40 h-screen overflow-hidden text-gray-700 bg-gray-100 rounded">

    <!-- Logo et nom de l'application -->
    <a class="flex items-center w-full px-3 mt-3">
        <img src="Images\logoMPT.png" class="rounded-lg">
    </a>
    <a class="flex items-center w-full px-3 mt-3">
        <span class="ml-2 text-sm font-bold">Musique Pour Tous</span>
    </a>

    <!-- Menu principal -->
    <div class="w-full px-2 mt-3 flex-grow sm:block sm:flex sm:flex-col sm:overflow-auto">

        <!-- Liens du menu -->
        <div class="flex flex-col items-center w-full border-t border-gray-300">
            <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=seance&action=liste">
                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-2 text-sm font-medium">Accueil</span>
            </a>

            <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=seance&action=ajouter">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
					<span class="ml-3 text-sm font-medium"> Créer Seance</span>
				</a>

				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=eleve&action=liste">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="9" cy="7" r="4"></circle>
						<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
					</svg>
					<span class="ml-2 text-sm font-medium">Adhérents</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=utilisateur&action=formInscription">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
					<span class="ml-3 text-sm font-medium">Ajouter Adhérent</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=classe&action=affichage">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
					<span class="ml-3 text-sm font-medium"> Classe</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=classe&action=creation">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
					<span class="ml-3 text-sm font-medium"> Créer Classe</span>
				</a>

        </div>

        <!-- Menu déroulant (hamburger) pour les petits écrans -->
        <div class="sm:hidden mt-4">
            <button id="menu-toggle" class="flex items-center px-3 py-2 border rounded text-gray-700 border-gray-700 hover:text-black hover:border-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                <span class="ml-2 text-sm font-medium">Menu</span>
            </button>
        </div>

    </div>

    <!-- Bouton de déconnexion -->
    <a class="flex items-center justify-center w-full h-16 mt-auto bg-gray-200 hover:bg-gray-300" href="index.php?uc=utilisateur&action=deconnexion">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 17l5-5-5-5M19.8 12H9M10 3H4v18h6"/>
        </svg>
        <span class="ml-2 text-sm font-medium">Se déconnecter</span>
    </a>
</div>

<!-- Script pour le menu hamburger -->
<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        let nav = document.querySelector('.flex-grow');
        nav.classList.toggle('hidden');
    });
</script>
