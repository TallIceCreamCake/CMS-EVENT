<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>CMS Configuration</title>
</head>

<body class="bg-gray-200 p-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-md shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Configuration du CMS</h1>

        <form id="configForm" action="process.php" method="post">
            <!-- Step 1 -->
            <div id="step1" class="mb-4">
                
<ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse">
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            1
        </span>
        <span>
            <h3 class="font-medium leading-tight">Infos CMS</h3>
            <p class="text-sm">Nom</p>
        </span>
    </li>
    <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
            2
        </span>
        <span>
            <h3 class="font-medium leading-tight">Base de donnée</h3>
            <p class="text-sm">Mysql</p>
        </span>
    </li>
    <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
            3
        </span>
        <span>
            <h3 class="font-medium leading-tight">Compte</h3>
            <p class="text-sm">Création du compte</p>
        </span>
    </li>
</ol><br>


                <label for="field1" class="block text-gray-700 text-sm font-bold mb-2">Nom de l'entreprise :</label>
                <input type="text" id="field1" name="field1" class="w-full border p-2 rounded">
            </div>

            <!-- Step 2 -->
            <div id="step2" class="mb-4 hidden">
                                
<ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse">
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            1
        </span>
        <span>
            <h3 class="font-medium leading-tight">Infos CMS</h3>
            <p class="text-sm">Nom</p>
        </span>
    </li>
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            2
        </span>
        <span>
            <h3 class="font-medium leading-tight">Base de donnée</h3>
            <p class="text-sm">Mysql</p>
        </span>
    </li>
    <li class="flex items-center text-gray-500 dark:text-gray-400 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400">
            3
        </span>
        <span>
            <h3 class="font-medium leading-tight">Compte</h3>
            <p class="text-sm">Création du compte</p>
        </span>
    </li>
</ol><br>
                <label for="dbHost" class="block text-gray-700 text-sm font-bold mb-2">IP/Liens de mysql :</label>
                <input type="text" id="dbHost" name="dbHost" class="w-full border p-2 rounded mb-2">

                <label for="dbName" class="block text-gray-700 text-sm font-bold mb-2">Nom de la base de donnée :</label>
                <input type="text" id="dbName" name="dbName" class="w-full border p-2 rounded mb-2">

                <label for="dbUser" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur :</label>
                <input type="text" id="dbUser" name="dbUser" class="w-full border p-2 rounded mb-2">

                <label for="dbPassword" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe de la base de donnée (optionnel):</label>
                <input type="password" id="dbPassword" name="dbPassword" class="w-full border p-2 rounded mb-4">
            </div>

            <!-- Step 3 -->
            <div id="step3" class="mb-4 hidden">
                                
<ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse">
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            1
        </span>
        <span>
            <h3 class="font-medium leading-tight">Infos CMS</h3>
            <p class="text-sm">Nom</p>
        </span>
    </li>
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            2
        </span>
        <span>
            <h3 class="font-medium leading-tight">Base de donnée</h3>
            <p class="text-sm">Mysql</p>
        </span>
    </li>
    <li class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            3
        </span>
        <span>
            <h3 class="font-medium leading-tight">Compte</h3>
            <p class="text-sm">Création du compte</p>
        </span>
    </li>
</ol><br>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" class="w-full border p-2 rounded mb-2">

                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe :</label>
                <input type="password" id="password" name="password" class="w-full border p-2 rounded mb-2">

                <label for="firstName" class="block text-gray-700 text-sm font-bold mb-2">Prénom :</label>
                <input type="text" id="firstName" name="firstName" class="w-full border p-2 rounded mb-2">

                <label for="lastName" class="block text-gray-700 text-sm font-bold mb-2">Nom :</label>
                <input type="text" id="lastName" name="lastName" class="w-full border p-2 rounded mb-2">

                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
                <input type="email" id="email" name="email" class="w-full border p-2 rounded mb-4">
            </div>

            <!-- Error message -->
            <div id="errorMessage" class="text-red-500 mb-4 hidden">Veuillez remplir tous les champs.</div>

            <!-- Buttons -->
            <div class="flex justify-between">
                <button type="button" onclick="prevStep()" id="prevBtn" class="bg-gray-400 text-white px-4 py-2 rounded hidden">Précédent</button>
                <button type="button" onclick="nextStep()" id="nextBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Suite</button>
                <button type="button" onclick="onSubmit()" id="finishBtn" class="bg-green-500 text-white px-4 py-2 rounded hidden">Terminer la config</button>
            </div>
        </form>

        <script>
            let currentStep = 1;

            function nextStep() {
                if (validateFields()) {
                    document.getElementById('errorMessage').classList.add('hidden');
                    document.getElementById(`step${currentStep}`).classList.add('hidden');
                    currentStep++;
                    document.getElementById(`step${currentStep}`).classList.remove('hidden');

                    document.getElementById('prevBtn').classList.remove('hidden');

                    if (currentStep === 3) {
                        document.getElementById('finishBtn').classList.remove('hidden');
                        document.getElementById('nextBtn').classList.add('hidden');
                    } else {
                        document.getElementById('finishBtn').classList.add('hidden');
                        document.getElementById('nextBtn').classList.remove('hidden');
                    }
                } else {
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            }

            function prevStep() {
                document.getElementById('errorMessage').classList.add('hidden');
                document.getElementById(`step${currentStep}`).classList.add('hidden');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.remove('hidden');

                if (currentStep === 1) {
                    document.getElementById('prevBtn').classList.add('hidden');
                    document.getElementById('finishBtn').classList.add('hidden');
                    document.getElementById('nextBtn').classList.remove('hidden');
                } else {
                    document.getElementById('finishBtn').classList.remove('hidden');
                    document.getElementById('nextBtn').classList.add('hidden');
                }
            }

            function validateFields() {
                if (currentStep === 1) {
                    const currentField = document.getElementById(`field${currentStep}`);
                    return currentField.value.trim() !== '';
                } else if (currentStep === 2) {
                    const dbHost = document.getElementById('dbHost').value.trim();
                    const dbName = document.getElementById('dbName').value.trim();
                    const dbUser = document.getElementById('dbUser').value.trim();
                    // Password is optional, so we don't check it

                    return dbHost !== '' && dbName !== '' && dbUser !== '';
                } else if (currentStep === 3) {
                    const username = document.getElementById('username').value.trim();
                    const password = document.getElementById('password').value.trim();
                    const firstName = document.getElementById('firstName').value.trim();
                    const lastName = document.getElementById('lastName').value.trim();
                    const email = document.getElementById('email').value.trim();

                    return username !== '' && password !== '' && firstName !== '' && lastName !== '' && email !== '';
                }

                return false;
            }

            function onSubmit() {
                if (validateFields()) {
                    document.getElementById('errorMessage').classList.add('hidden');
                    document.getElementById('configForm').submit();
                } else {
                    document.getElementById('errorMessage').classList.remove('hidden');
                }
            }
        </script>
    </div>
</body>

</html>
