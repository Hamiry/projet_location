<?php
// On établit la connexion avec la base de données
$con = mysqli_connect('localhost', 'root');
// On sélectionne la base de données cars
mysqli_select_db($con, 'cars');

// On récupère les valeurs des inputs dans le formulaire pour les stocker dans des variables
$username = $_POST['username'];
$password = $_POST['password'];

// Vérifier si le compte existe déjà dans la table "accounts"
$queryTest = "SELECT * FROM accounts WHERE username = '$username'";
$result = mysqli_query($con, $queryTest);

if (mysqli_num_rows($result) > 0) {
    // Le compte existe déjà
    $response = array(
        'success' => false,
        'message' => "Ce nom d'utilisateur est déjà utilisé"
    );
    echo json_encode($response);
    exit();
}

// Hasher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insérer les valeurs dans la table "accounts"
$query = "INSERT INTO accounts (username, password) VALUES ('$username', '$hashedPassword')";
if (mysqli_query($con, $query)) {
    // L'inscription a réussi
    $response = array(
        'success' => true,
        'message' => "Inscription réussie !"
    );
    echo json_encode($response);
    exit();
} else {
    // Une erreur s'est produite lors de l'insertion
    $response = array(
        'success' => false,
        'message' => "Une erreur s'est produite lors de l'inscription. Veuillez réessayer."
    );
    echo json_encode($response);
    exit();
}
