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

        <form id="ajoutEleve" class="mt-8 sm:mt-0 md:mt-0 lg:mt-0 ml-4 sm:ml-8 md:ml-16 lg:ml-24 max-w-lg" action="index.php?uc=classe&action=ajoutEleve" method="post">
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

                                        <div class="pl-3">
                                            <div class="text-base font-semibold"><?=$instrument->getLIBELLE()?></div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-6">
                                            <button class="shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">Ajouter</button>
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
                

        </form>
    </div>

    <script>
        function submitForm() {
            document.getElementById('instrumentForm').submit();
        }
    </script>
</section>