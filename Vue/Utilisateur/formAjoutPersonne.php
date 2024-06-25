<?php
if($_SESSION["autorisation"] == "emp"){
  include("Vue/navbar.php");
}
else{
  include("Vue/navbarEleveProf.php");
}  ?>
<section style="" class="bg-cover min-h-screen flex items-center justify-center ">
    <div class="w-full sm:max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
        <form id="form" class="mt-10" action="index.php?uc=utilisateur&action=inscription" method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                    Rôle
                </label>
                <select id="role" name="role" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white">
                    <option value="1" <?php if ($role == 1 || ($role === "")) echo 'selected'; ?>>Admin</option>
                    <option value="2" <?php if ($role == 2) echo 'selected'; ?>>Élève</option>
                    <option value="3" <?php if ($role == 3) echo 'selected'; ?>>Professeur</option>

                </select>
                <?php if (!empty($_SESSION['erreurs']['role'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['role']; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom
                        </label>
                        <input name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="nom" type="text" placeholder="Jane" required>
                        <?php if (!empty($_SESSION['erreurs']['nom'])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['nom']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="prenom">
                            Prénom
                        </label>
                        <input name="prenom" value="<?php echo htmlspecialchars($prenom ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="prenom" type="text" placeholder="Doe" required>
                        <?php if (!empty($_SESSION['erreurs']['prenom'])): ?>
                            <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['prenom']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="mail">
                    E-mail
                </label>
                <input name="mail" value="<?php echo htmlspecialchars($mail ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="mail" type="email" placeholder="user@example.me" required>
                <?php if (!empty($_SESSION['erreurs']['mail'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['mail']; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="mdp">
                    Mot de passe
                </label>
                <input name="mdp" value="<?php echo htmlspecialchars($mdp ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="mdp" type="password" placeholder="••••••••" required>
                <?php if (!empty($_SESSION['erreurs']['mdp'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['mdp']; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_mdp">
                    Confirmer Mot de passe
                </label>
                <input name="confirm_mdp" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="confirm_mdp" type="password" placeholder="••••••••" required>
                <p id="mdp_error" class="text-red-500 text-xs italic" style="display: none;">Les mots de passe ne correspondent pas.</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="adresse">
                    Adresse
                </label>
                <input name="adresse" value="<?php echo htmlspecialchars($adresse ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="adresse" type="text" placeholder="24 place de la concorde, 75015 Paris" required>
                <?php if (!empty($_SESSION['erreurs']['adresse'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['adresse']; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="telephone">
                    Téléphone
                </label>
                <input name="telephone" value="<?php echo htmlspecialchars($telephone ?? ''); ?>" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="telephone" type="text" placeholder="+0654899585" required>
                <?php if (!empty($_SESSION['erreurs']['telephone'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['telephone']; ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4" id="instruments-container">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Instruments
                </label>
                <ul id="instruments-list">
                    <?php if (isset($LesInstruments) && is_array($LesInstruments)): ?>
                        <?php foreach ($LesInstruments as $instrument): ?>
                            <li class="mb-2">
                                <input type="checkbox" class="instrument-checkbox" id="instrument<?= $instrument->getIDINSTRUMENT() ?>" name="instruments[]" value="<?= $instrument->getIDINSTRUMENT() ?>" <?php if (in_array($instrument->getIDINSTRUMENT(), $instruments ?? [])) echo 'checked'; ?>>
                                <label for="instrument<?= $instrument->getIDINSTRUMENT() ?>"><?= $instrument->getLIBELLE() ?></label>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-red-500 text-xs italic">Aucun instrument disponible.</p>
                    <?php endif; ?>
                </ul>
                <?php if (!empty($_SESSION['erreurs']['instruments'])): ?>
                    <p class="text-red-500 text-xs italic"><?php echo $_SESSION['erreurs']['instruments']; ?></p>
                <?php endif; ?>
            </div>

            <div class="flex justify-center">
                <button id="submitbtn" class="bg-orange-400 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</section>

<?php
unset($_SESSION['erreurs']);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const instrumentsContainer = document.getElementById('instruments-container');
    const instrumentCheckboxes = document.querySelectorAll('.instrument-checkbox');
    const mdp = document.getElementById('mdp');
    const confirmMdp = document.getElementById('confirm_mdp');
    const mdpError = document.getElementById('mdp_error');
    const form = document.getElementById('form');

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

    function validatePasswords() {
        if (mdp.value !== confirmMdp.value) {
            mdpError.style.display = 'block';
        } else {
            mdpError.style.display = 'none';
        }
    }

    mdp.addEventListener('input', validatePasswords);
    confirmMdp.addEventListener('input', validatePasswords);

    form.addEventListener('submit', function(event) {
        if (mdp.value !== confirmMdp.value) {
            event.preventDefault();
            mdpError.style.display = 'block';
        }
    });
});
</script>

<?php include("Vue/footer.php"); ?>