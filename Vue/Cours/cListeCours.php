<div>
    <?php include("Vue/navbar.php"); ?>
    
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="m-2 bg-red-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="sm:ml-40">

    

        <div class="flex items-center justify-between p-4 bg-white">
            <div></div>
            <div class="relative">
                <form class="flex items-center" action="index.php?uc=cours&action=recherche" method="post">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" name="recherche" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Rechercher un cours">
                    <button class="ml-2 shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-1 px-2 rounded" type="submit">
                        Chercher
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($lesCours as $cours) { ?>
                <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow-lg relative transition-none">
                    <div class="p-4">
                        <div class="text-base font-semibold"><?= $cours->getInstrument() ?></div>
                        <div class="text-gray-500">Professeur: <?= $cours->getNomProfesseur() ?> <?= $cours->getPrenomProfesseur() ?></div>
                        <div class="mt-2">
                            <div class="font-semibold">Date:</div>
                            <div><?= htmlspecialchars($cours->getdate()) ?></div>
                        </div>
                        <div class="mt-2">
                            <div class="font-semibold">Heure Début:</div>
                            <div><?= htmlspecialchars($cours->getHeureDebut()) ?></div>
                        </div>
                        <div class="mt-2">
                            <div class="font-semibold">Heure Fin:</div>
                            <div><?= htmlspecialchars($cours->getHeureFin()) ?></div>
                        </div>
                    </div>
                    <div class="absolute bottom-4 right-4 sm:static sm:flex sm:justify-end sm:p-4">
                        <div class="flex flex-col space-y-2">
                            <a class="flex items-center bg-orange-400 h-10 px-3 rounded hover:bg-gray-300" href="index.php?uc=eleve&action=inscription&idprof=<?= $cours->IDPROF ?>&nums=<?= $cours->NUMSEANCE ?>&jour=<?= $cours->JOUR ?>&tranche=<?= $cours->TRANCHE ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                <span class="ml-2 text-white text-xs font-medium">Adhérent</span>
                            </a>
                            <a class="flex items-center bg-yellow-500 h-10 px-3 rounded hover:bg-yellow-600" href="index.php?uc=inscriptions&action=liste&idprof=<?= $cours->IDPROF ?>&nums=<?= $cours->NUMSEANCE ?>&jour=<?= $cours->JOUR ?>&tranche=<?= $cours->TRANCHE ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <line x1="20" y1="8" x2="20" y2="14"></line>
                                    <line x1="23" y1="11" x2="17" y2="11"></line>
                                </svg>
                                <span class="ml-2 text-white text-xs font-medium">Modifier</span>
                            </a>
                            <a class="flex items-center bg-blue-900 h-10 px-3 rounded hover:bg-gray-300" href="index.php?uc=inscriptions&action=liste&idprof=<?= $cours->IDPROF ?>&nums=<?= $cours->NUMSEANCE ?>&jour=<?= $cours->JOUR ?>&tranche=<?= $cours->TRANCHE ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span class="ml-2 text-white text-xs font-medium">Inscriptions</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
