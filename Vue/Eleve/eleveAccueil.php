
<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
else{
  include("Vue/navbarEleveProf.php");
}  ?>  
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="m-2 bg-red-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['Sucess'])): ?>
        <div class="m-2 bg-green-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['Sucess']; ?>
        </div>
        <?php unset($_SESSION['Sucess']); ?>
    <?php endif; ?>
    <div class="sm:ml-40">

    
        
        <div class="flex items-center justify-between p-4 bg-white">
            <div class="flex items-center w-full md:w-auto transition-all duration-300"> 
                    <div class="relative">
                        <form class="flex items-center" action="index.php?uc=eleve&action=recherche" method="post">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 " aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" name="recherche" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-40 md:w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " placeholder="Rechercher un adhérent"> 
                            <button class="ml-2 shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-1 px-2 rounded" type="submit">
                                Chercher
                            </button>
                        </form>
                    </div>
            </div>
            <div></div> 
        </div>
        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
            <?php 
            if (isset($lesCours)){

            foreach ($lesCours as $cours) { ?>
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
                </div>
            
            <?php }
            }
            ?>
        </div>
    </div>
    <?php include("Vue/footer.php"); ?>