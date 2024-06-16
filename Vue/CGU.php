<?php
if ($_SESSION["autorisation"] == "emp") {
    include("Vue/navbar.php");
} else {
    include("Vue/navbarEleveProf.php");
}
?>
<div class="sm:ml-40">

    
    <main>
        <!-- Contenu principal -->
        <div class="space-y-8">
            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">1. Introduction</h2>
                <p class="text-gray-700">Les présentes Conditions Générales d'Utilisation (ci-après dénommées "CGU") régissent l'utilisation du site web "Musique Pour Tous" (ci-après dénommé le "Site") ainsi que des services proposés par Musique Pour Tous (ci-après dénommé "nous", "notre" ou "nos"). En accédant au Site ou en utilisant nos services, vous acceptez d'être lié par ces CGU.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">2. Accès au Site</h2>
                <p class="text-gray-700">Vous devez avoir l'âge légal pour utiliser ce Site. En accédant au Site, vous garantissez que vous êtes légalement capable de conclure des contrats contraignants et que vous utilisez le Site conformément aux lois et réglementations applicables.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">3. Utilisation du Site</h2>
                <p class="text-gray-700">Le Site "Musique Pour Tous" permet l'organisation en ligne de cours de musique. Les élèves et les professeurs peuvent se connecter pour consulter leur planning et les informations relatives à leurs cours. Les administrateurs peuvent ajouter, supprimer et modifier des classes, des séances et des utilisateurs sur le site.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">4. Propriété Intellectuelle</h2>
                <p class="text-gray-700">Tous les contenus présents sur le Site, y compris les logos créés par Musique Pour Tous, sont la propriété de Musique Pour Tous ou de ses fournisseurs de contenu et sont protégés par les lois sur le droit d'auteur et autres lois sur la propriété intellectuelle.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">5. Protection des Données Personnelles</h2>
                <p class="text-gray-700">Nous ne collectons aucune donnée personnelle des utilisateurs sur le Site. Les variables de session sont stockées temporairement pour faciliter la connexion et sont détruites lors de la déconnexion.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">6. Limitation de Responsabilité</h2>
                <p class="text-gray-700">Dans les limites permises par la loi, nous déclinons toute responsabilité pour tout dommage direct, indirect, spécial, consécutif ou punitif découlant de l'utilisation ou de l'incapacité d'utiliser le Site ou les services.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">7. Modification des CGU</h2>
                <p class="text-gray-700">Nous nous réservons le droit de modifier ces CGU à tout moment, par notification par email. Il est de votre responsabilité de consulter régulièrement ces CGU pour vous assurer que vous comprenez et acceptez les conditions actuelles.</p>
            </div>

            <div class="section bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">8. Droit Applicable et Juridiction</h2>
                <p class="text-gray-700">Les présentes CGU sont régies par les lois de la France. Tout litige découlant de ces CGU sera soumis à la juridiction exclusive des tribunaux français.</p>
            </div>

            <div class="contact bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">9. Contact</h2>
                <p class="text-gray-700">Pour toute question concernant ces CGU, veuillez nous contacter à l'adresse suivante :</p>
                <p><a href="mailto:florian.germain@gmail.com" class="text-blue-500">florian.germain@gmail.com</a></p>
            </div>
        </div>
    </main>

    <?php include("Vue/footer.php"); ?>

</div>
