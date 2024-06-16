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


<section style="background-image: url('Images/music.jpg');" class="bg-cover min-h-screen flex items-center justify-center bg-violet-600">



    <div class="w-full max-w-lg px-6 py-8 bg-white rounded-lg shadow-md">
        <form id="instrumentForm" class="space-y-4" action="index.php?uc=seance&action=ajouter" method="post">
            <div class="mb-4">
                <label for="idInstrument" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Instrument:</label>
                <select id="idInstrument" name="idInstrument" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" required onchange="submitForm()">
                    <option value="" disabled selected>Choisir un instrument</option>
                    <!-- Remplir avec les instruments disponibles -->
                    <?php foreach ($lesInstruments as $instrument): ?>
                        <option value="<?= $instrument->getIDINSTRUMENT() ?>" <?= (isset($idInstruments) && $idInstruments == $instrument->getIDINSTRUMENT()) ? 'selected' : '' ?>>
                            <?= $instrument->getLIBELLE() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            <?php if(isset($idInstruments)): ?>
                <div class="mb-4">
                    <label for="dateSeance" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Date et Heure de la Séance:</label>
                    <?php if(!isset($dateSeance)): ?>
                        <input type="datetime-local"
                               id="dateSeance"
                               name="dateSeance"
                               value="<?= $formattedDate ?>"
                               min="<?= $formattedDate ?>"
                               max="<?= $formattedDatePlusOneYear ?>"
                               class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                               onchange="submitDateForm()"
                        />
                    <?php else: ?>
                        <input type="datetime-local"
                               id="dateSeance"
                               name="dateSeance"
                               value="<?= "{$annee}-{$mois}-{$jour}T{$heure}" ?>"
                               min="<?= $formattedDate ?>"
                               max="<?= $formattedDatePlusOneYear ?>"
                               class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                               onchange="submitDateForm()"
                        />
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($dateSeance)): ?>
                <div class="mb-4">
                    <label for="idProfesseur" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Professeur:</label>
                    <select id="idProfesseur" name="idProfesseur" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                        <option value="" disabled selected>Professeur disponible pour le cours</option>
                        <!-- Remplir avec les instruments disponibles -->
                        <?php foreach ($profsDisponibles as $prof): ?>
                            <option value="<?= $prof->getIDPROF() ?>">
                                <?= "{$prof->getNom()} {$prof->getPrenom()}" ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="idClasse" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Classe:</label>
                    <select id="idClasse" name="idClasse" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                        <option value="" disabled selected>Classe disponible pour le cours</option>
                        <!-- Remplir avec les instruments disponibles -->
                        <?php foreach ($classesDisponibles as $classe): ?>
                            <option value="<?= $classe->getIDCLASSE() ?>">
                                <?= "Classe {$classe->getIDCLASSE()}" ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="flex justify-center">
                <button id="submitbtn" class="shadow bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    function submitForm() {
        document.getElementById('instrumentForm').submit();
    }

    function submitDateForm() {
        document.getElementById('instrumentForm').submit();
    }
</script>

<?php include("Vue/footer.php"); ?>