<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté, si oui, le rediriger vers la page d'accueil
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On établit la connexion avec la base de données
    $con = mysqli_connect('localhost', 'root');
    // On sélectionne la base de données appropriée
    mysqli_select_db($con, 'cars');

    // Récupérer les valeurs saisies dans le formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Effectuer la validation des identifiants
    $query = "SELECT * FROM accounts WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // Les identifiants sont valides

            // Stocker le nom d'utilisateur dans la session
            $_SESSION['username'] = $username;

            // Rediriger vers la page d'accueil
            header('Location: index.php');
            exit();
        } else {
            // Mot de passe incorrect
            $error_message = "Mot de passe incorrect. Veuillez réessayer.";
        }
    } else {
        // Utilisateur non trouvé
        $error_message = "Nom d'utilisateur incorrect. Veuillez réessayer.";
    }

    // Fermer la connexion à la base de données
    mysqli_close($con);
}
?>

<?php include '../templates/header.php' ?>

<h1 class="text-center m-3">Connexion</h1>
<div class="content">
    <form action="login.php" method="post" id="connexion" class="shadow p-4 rounded w-25">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-2">Se connecter</button>
        </div>
    </form>

    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
    <?php endif; ?>
</div>
<p class="text-center mt-3">Pas encore inscrit ? <a href="sign-in.php">Inscris-toi</a></p>

<?php include '../templates/footer.php' ?>
