<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
else{
  include("Vue/navbarEleveProf.php");
}
?>
<section class="bg-cover bg-center min-h-screen overflow-hidden p-12 bg-violet-600" style="background-image: url('Images/music.jpg');">
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
        <form id="instrumentForm" class="mt-8 sm:mt-0 md:mt-0 lg:mt-0 ml-4 sm:ml-8 md:ml-16 lg:ml-24 max-w-lg" action="index.php?uc=classe&action=creation" method="post">

            <div class="flex flex-wrap -mx-3 mb-6">
                <label for="idInstrument" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Instrument:</label>
                <select id="idInstrument" name="idInstrument" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" required onchange="submitForm()">
                    <option value="" disabled selected>Choisir un instrument</option>

                    <?php foreach ($lesInstruments as $instrument): ?>
                        <option value="<?= $instrument->getIDINSTRUMENT() ?>" <?php if(isset($idInstruments) && $idInstruments == $instrument->getIDINSTRUMENT()){ echo("selected"); } ?>><?= $instrument->getLIBELLE() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <form id="ajoutEleve" class="mt-8 sm:mt-0 md:mt-0 lg:mt-0 ml-4 sm:ml-8 md:ml-16 lg:ml-24 max-w-lg" action="index.php?uc=classe&action=ajoutEleve" method="post">
            <?php if(isset($lesEleves) && $lesEleves != NULL): ?>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Adhérent</th>
                                <th scope="col" class="px-6 py-3 hidden sm:table-cell transition-all duration-500">Téléphone</th>
                                <th scope="col" class="px-6 py-3 hidden md:table-cell transition-all duration-500">Adresse</th>
                                <th scope="col" class="px-6 py-3 hidden lg:table-cell transition-all duration-500">Mail</th>
                                <th scope="col" class="px-6 py-3">Ajouter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lesEleves as $eleve): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">

                                        <div class="pl-3">
                                            <div class="text-base font-semibold"><?= $eleve->getPrenom() ?> <?= $eleve->getNom() ?></div>
                                            <div class="font-normal text-gray-500 lg:hidden">Mail: <?= $eleve->getMail() ?></div>
                                            <div class="font-normal text-gray-500 md:hidden">Adresse: <?= $eleve->getAdresse() ?></div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 hidden sm:table-cell transition-all duration-500"><?= $eleve->getTelephone() ?></td>
                                    <td class="px-6 py-4 hidden md:table-cell transition-all duration-500"><?= $eleve->getAdresse() ?></td>
                                    <td class="px-6 py-4 hidden lg:table-cell transition-all duration-500"><?= $eleve->getMail() ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-6">
                                            <input type="checkbox" id="eleve<?= $eleve->getIDELEVE() ?>" name="eleves[]" value="<?= $eleve->getIDELEVE() ?>" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="flex justify-center mt-6">
                        <button class="shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">Ajouter</button>
                    </div>
                </div>
                <input type="hidden" id="idinstrument" name="idinstrument" value="<?= $idInstruments ?>">
                
            <?php endif; ?>
        </form>
    </div>

    <script>
        function submitForm() {
            document.getElementById('instrumentForm').submit();
        }
    </script>
</section>
