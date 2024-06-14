<div>
    <?php include("Vue/navbar.php"); ?>
</div>

<div class="sm:ml-40">
    <?php
    if (!empty($_SESSION['message'])) {
        ?>
        <div class="m-2 bg-red-200 font-semibold flex justify-center alert alert-success" role="alert" data-auto-dismiss="2000">
            <?php 
            echo($_SESSION["message"]); 
            unset($_SESSION["message"]); 
            ?>
        </div>
        <?php
    }
    ?>


    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Classe
                </th>
                <th scope="col" class="px-6 py-3">
                    Instrument
                </th>
                <th scope="col" class="px-6 py-3">
                    Nombre D'élèves
                </th>
                <th scope="col" class="px-6 py-3"></th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($lesClasses as $classe) {
                $nombreEleves = count($classe->getEleves());
                ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                            <path d="M14 3v5h5"/>
                            <path d="M16 13H8"/>
                            <path d="M16 17H8"/>
                            <path d="M10 9H8"/>
                        </svg>
                        <div class="pl-3">
                            <div class="text-base font-semibold">Classe <?=$classe->getIDCLASSE()?></div>
                        </div>  
                    </th>
                    <td class="px-6 py-4">
                        <?=$classe->getNomInstrument()?>
                    </td>
                    <td class="px-6 py-4">
                        <?=$nombreEleves?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="mt-4 mr-0 mb-0 ml-0 pt-0 pr-0 pb-0 pl-14 flex items-center sm:space-x-6 sm:pl-0 sm:mt-0">
                            <a class="flex items-center bg-yellow-500 h-12 px-3 mt-2 rounded hover:bg-yellow-600" href="index.php?uc=classe&action=modifier&idclasse=<?=$classe->getIDCLASSE()?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19.5l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                                <span class="ml-3 text-white text-sm font-medium">Modifier</span>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="mt-4 mr-0 mb-0 ml-0 pt-0 pr-0 pb-0 pl-14 flex items-center sm:space-x-6 sm:pl-0 sm:mt-0">
                            <a class="flex items-center bg-red-600 h-12 px-3 mt-2 rounded hover:bg-gray-300" href="index.php?uc=classe&action=supprimer&idClasse=<?=$classe->getIDCLASSE()?>">
                                <i class="fas fa-trash-alt text-white"></i>
                                <span class="ml-3 text-white text-sm font-medium">Supprimer</span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
            }   
            ?>
        </tbody>
    </table>
</div>
