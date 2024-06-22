<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
?>
<section class="bg-cover bg-center min-h-screen overflow-hidden p-12 bg-violet-600" style="background-image: url('Images/music.jpg');">
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="m-2 bg-red-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="sm:ml-40">
        <div class="flex items-center justify-between p-4 bg-white">
                <div class="flex items-center w-full md:w-auto transition-all duration-300"> 
                        <div class="relative">
                            <form class="flex items-center" action="index.php?uc=instrument&action=ajouter" method="post">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-500 " aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" name="instrument" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-40 md:w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " placeholder="Rechercher un adhérent"> 
                                <button class="ml-2 shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-1 px-2 rounded" type="submit">
                                    Chercher
                                </button>
                            </form>
                        </div>
                </div>
                <div></div> 
            </div>

        <form id="supprimerInstrument" class="mt-8 sm:mt-0 md:mt-0 lg:mt-0 ml-4 sm:ml-8 md:ml-16 lg:ml-24 max-w-lg" action="index.php?uc=instrument&action=supprimer" method="post">
                <div class="flex flex-wrap -mx-3 mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Instrument</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lesInstruments as $instrument): ?>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <input type="hidden" id="idinstrument" name="idinstrument" value="<?=$instrument->getIDINSTRUMENT()?>">
                                        <div class="pl-3">
                                            <div class="text-base font-semibold"><?=$instrument->getLIBELLE()?></div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-6">
                                            <button class="shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">Supprimer</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                

        </form>
    </div>

    <script>
        function submitForm() {
            document.getElementById('instrumentForm').submit();
        }
    </script>
</section>