<div>
<?php
    include("Vue/navbar.php");
?>
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="m-2 bg-red-200 text-white font-semibold flex justify-center p-4 rounded-md" role="alert" data-auto-dismiss="2000">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
<section style="background-image: url('Images/music.jpg');" class="bg-cover min-h-screen flex items-center justify-center bg-violet-600">

    

<div class="w-full sm:max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
    <form id="form" class="mt-10" action="index.php?uc=utilisateur&action=inscription" method="post">
        <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
            Rôle
        </label>
        <select id="role" name="role" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
            <option value="1">Admin</option>
            <option value="2">Élève</option>
            <option value="3">Professeur</option>
        </select>
        </div>
        <div class="mb-4">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                        Nom
                    </label>
                    <input name="nom" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="nom" type="text" placeholder="Jane" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="prenom">
                        Prénom
                    </label>
                    <input name="prenom" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="prenom" type="text" placeholder="Doe" required>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="mail">
                E-mail
            </label>
            <input name="mail" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="mail" type="email" placeholder="user@example.me" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="mdp">
                Mot de passe
            </label>
            <input name="mdp" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="mdp" type="password" placeholder="••••••••" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="adresse">
                Adresse
            </label>
            <input name="adresse" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="adresse" type="text" placeholder="24 place de la concorde, 75015 Paris" required>
        </div>

        <div class="mb-4">
            <div class="w-full px-3">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telephone">
                    Téléphone
                </label>
                <input name="telephone" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="telephone" type="text" placeholder="+0654899585" required>
            </div>
        </div>

        <div class="mb-4" id="instruments-container">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Instruments
            </label>
            <ul id="instruments-list">
                <?php foreach ($LesInstruments as $instrument) { ?>
                    <li class="mb-2">
                        <input type="checkbox" class="instrument-checkbox" id="instrument<?= $instrument->getIDINSTRUMENT() ?>" name="instruments[]" value="<?= $instrument->getIDINSTRUMENT() ?>">
                        <label for="instrument<?= $instrument->getIDINSTRUMENT() ?>"><?= $instrument->getLIBELLE() ?></label>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="flex justify-center">
            <button id="submitbtn" class="bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                Ajouter
            </button>
        </div>
    </form>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const instrumentsContainer = document.getElementById('instruments-container');
    const instrumentCheckboxes = document.querySelectorAll('.instrument-checkbox');

    function updateInstrumentOptions() {
        const role = roleSelect.value;
        if (role === '1') { 
            instrumentsContainer.style.display = 'none';
            instrumentCheckboxes.forEach(checkbox => checkbox.checked = false);
        } else if (role === '2') { 
            instrumentsContainer.style.display = 'block';
            instrumentCheckboxes.forEach(checkbox => {
                checkbox.type = 'radio';
                checkbox.name = 'instruments[]';
            });
        } else if (role === '3') { 
            instrumentsContainer.style.display = 'block';
            instrumentCheckboxes.forEach(checkbox => {
                checkbox.type = 'checkbox';
                checkbox.name = 'instruments[]';
            });
        }
    }

    roleSelect.addEventListener('change', updateInstrumentOptions);
    updateInstrumentOptions(); 
});
</script>
