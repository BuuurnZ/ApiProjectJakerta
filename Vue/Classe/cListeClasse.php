<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
else{
  include("Vue/navbarEleveProf.php");
}  ?>
<div class="sm:ml-40">
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
     
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4">
        <?php
        if(isset($lesClasses)){

        foreach ($lesClasses as $classe) {

            $nombreEleves = count($classe->getEleves());
        ?>
            <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow-lg relative">
                <div class="p-4">
                    <div class="text-base font-semibold">Classe <?= $classe->getIDCLASSE() ?></div>
                    <div class="text-gray-500"><?= $classe->getNOMINSTRUMENT() ?></div>
                    <div class="mt-2">
                        <div class="font-semibold">Nombre d'élèves:</div>
                        <div><?= $nombreEleves ?></div>
                    </div>
                </div>
                <div class="absolute bottom-4 right-4 sm:static sm:flex sm:justify-end sm:p-4">
                    <a class="flex items-center bg-yellow-500 h-10 px-3 mt-2 rounded hover:bg-yellow-600" href="index.php?uc=classe&action=modifier&idclasse=<?= $classe->getIDCLASSE() ?>&idinstrument=<?= $classe->getIDINSTRUMENT() ?>">
                        <img src="Images/pen.png" alt="Icône Liste Adhérents">  
                        <span class="ml-2 text-white text-xs font-medium">Modifier</span>
                    </a>
                    <a class="flex items-center bg-red-600 h-10 px-3 mt-2 rounded ml-2 hover:bg-gray-300" href="index.php?uc=classe&action=supprimer&idClasse=<?= $classe->getIDCLASSE() ?>"  onclick="confirmDeletion(event)">
                        <img src="Images/bin.png" alt="Icône Liste Adhérents">  
                        <span class="ml-2 text-white text-xs font-medium">Supprimer</span>
                    </a>
                </div>
            </div>
        <?php
        }
    }

        ?>
    </div>
</div>
<script>
        function confirmDeletion(event) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur ?')) {
                event.preventDefault();
            }
        }
    </script>
<?php include("Vue/footer.php"); ?>