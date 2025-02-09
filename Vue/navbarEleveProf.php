
<div class="fixed z-10 left-0 transition-transform transform -translate-x-full sm:translate-x-0 items-center w-40 h-screen overflow-hidden text-gray-700 bg-gray-100 rounded sm:flex sm:flex-col">

    <a class="flex items-center w-full px-3 mt-3">
        <img src="Images/logoMPT.png" class="rounded-lg">
    </a>
    <a class="flex items-center w-full px-3 mt-3">
        <span class="ml-2 text-sm font-bold">Musique Pour Tous</span>
    </a>


    <div class="w-full px-2 mt-3 flex-grow hidden sm:block" id="main-menu">

        <div class="flex flex-col items-center w-full border-t border-gray-300">

            <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=<?=$_SESSION["autorisation"]?>&action=liste">
                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-2 text-sm font-medium">Accueil</span>
            </a>	
        </div>
    </div>


		<a class="flex items-center justify-center w-full h-16 mt-auto bg-gray-200 hover:bg-gray-300" href="index.php?uc=utilisateur&action=deconnexion">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M16 17l5-5-5-5M19.8 12H9M10 3H4v18h6"/>
			</svg>
			<span class="ml-2 text-sm font-medium">Se déconnecter</span>
		</a>
</div>


<div class="sm:hidden mt-4 absolute top-0 right-0 m-4">
    <button id="menu-toggle" class="flex items-center px-3 py-2 border rounded text-gray-700 border-gray-700 hover:text-black hover:border-black">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
        <span class="ml-2 text-sm font-medium">Menu</span>
    </button>


    <div id="dropdown-menu" class="hiddenflex flex-col items-center w-40 bg-gray-100 rounded shadow-lg absolute top-12 right-0 z-20">

        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=<?=$_SESSION["autorisation"]?>&action=liste">
            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="ml-2 text-sm font-medium">Accueil</span>
        </a>

		<a class="flex items-center justify-center w-full h-16 mt-auto bg-gray-200 hover:bg-gray-300" href="index.php?uc=utilisateur&action=deconnexion">
        	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            	<path d="M16 17l5-5-5-5M19.8 12H9M10 3H4v18h6"/>
        	</svg>
        	<span class="ml-2 text-sm font-medium">Se déconnecter</span>
    	</a>
    </div>
</div>


<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        let dropdownMenu = document.getElementById('dropdown-menu');
        dropdownMenu.classList.toggle('hidden');
    });
</script>
