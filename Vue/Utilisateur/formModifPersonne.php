<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
else{
  include("Vue/navbarEleveProf.php");
}  ?>
<section style="background-image: url('Images/music.jpg');" class="bg-cover h-screen overflow-auto p-12 bg-violet-600">

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="m-2 bg-red-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="w-full max-w-lg mx-auto bg-white rounded-lg shadow-md">
        <form id="form" class="mt-10 px-6 py-8" action="index.php?uc=utilisateur&action=modifier&idutilisateur=<?= $utilisateur->getIdutilisateur() ?>" method="post">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                    Rôle
                </label>
                <select id="role" name="role" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                    <option value="1" <?php if ($utilisateur instanceof Utilisateur) echo "selected"; ?>>Admin</option>
                    <option value="2" <?php if ($utilisateur instanceof Eleve) echo "selected"; ?>>Élève</option>
                    <option value="3" <?php if ($utilisateur instanceof Professeur) echo "selected"; ?>>Professeur</option>
                </select>
            </div>

            <div class="mb-4">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom
                        </label>
                        <input id="nom" name="nom" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="Jane" value="<?= $utilisateur->getNom() ?>" required>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prenom">
                            Prénom
                        </label>
                        <input id="prenom" name="prenom" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="Doe" value="<?= $utilisateur->getPrenom() ?>" required>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="mail">
                    E-mail
                </label>
                <input id="mail" name="mail" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="email" placeholder="user@example.me" value="<?= $utilisateur->getMail() ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="mdp">
                    Mot de passe
                </label>
                <input id="mdp" name="mdp" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="password" placeholder="••••••••">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_mdp">
                    Confirmer Mot de passe
                </label>
                <input id="confirm_mdp" name="confirm_mdp" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="password" placeholder="••••••••">
                <p id="mdp_error" class="text-red-500 text-xs italic" style="display: none;">Les mots de passe ne correspondent pas.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="adresse">
                    Adresse
                </label>
                <input id="adresse" name="adresse" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="24 place de la concorde, 75015 Paris" value="<?= $utilisateur->getAdresse() ?>" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telephone">
                    Téléphone
                </label>
                <input id="telephone" name="telephone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" type="text" placeholder="+0654899585" value="<?= $utilisateur->getTelephone() ?>" required>
            </div>

            <div class="mb-4 instruments-container">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Instruments
                </label>
                <ul>
                    <?php foreach ($LesInstruments as $instrument): ?>
                        <li>
                            <input type="checkbox" id="instrument<?= $instrument->getIDINSTRUMENT() ?>" name="instruments[]" value="<?= $instrument->getIDINSTRUMENT() ?>" <?php if (in_array($instrument->getIDINSTRUMENT(), $utilisateur->getInstruments())) echo 'checked'; ?>>
                            <label for="instrument<?= $instrument->getIDINSTRUMENT() ?>"><?= $instrument->getLIBELLE() ?></label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="flex justify-center">
                <button id="submitbtn" class="bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                    Modifier
                </button>
            </div>

            <?php if ($utilisateur instanceof Eleve): ?>
                <input type="hidden" id="ideleve" name="ideleve" value="<?= $utilisateur->getIDELEVE() ?>">
            <?php elseif ($utilisateur instanceof Professeur): ?>
                <input type="hidden" id="idprofesseur" name="idprofesseur" value="<?= $utilisateur->getIDPROFESSEUR() ?>">
            <?php endif; ?>

        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const instrumentsContainer = document.querySelector('.instruments-container'); 
        const instrumentCheckboxes = document.querySelectorAll('input[type="checkbox"]');
        const instrumentLabels = document.querySelectorAll('.instruments-container label');

        function updateInstrumentOptions() {
            const role = roleSelect.value;
            if (role === '1') { 
                instrumentsContainer.style.display = 'none';
                instrumentCheckboxes.forEach(checkbox => checkbox.checked = false);
            } else { 
                instrumentsContainer.style.display = 'block';
                if (role === '2') { 
                    instrumentCheckboxes.forEach(checkbox => {
                        checkbox.type = 'radio';
                    });
                } else if (role === '3') { 
                    instrumentCheckboxes.forEach(checkbox => {
                        checkbox.type = 'checkbox';
                    });
                }
            }
        }

        roleSelect.addEventListener('change', updateInstrumentOptions);
        updateInstrumentOptions(); 
    });
</script>