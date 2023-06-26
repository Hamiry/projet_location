<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.php");
    exit;
}

$conn = mysqli_connect('localhost', 'root', '', 'cars');

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];
    $username = $_SESSION['username'];

    // Obtenir l'ID de l'utilisateur à partir de la table "accounts"
    $userIdQuery = "SELECT ID FROM accounts WHERE username = '$username'";
    $userIdResult = mysqli_query($conn, $userIdQuery);
    $row = mysqli_fetch_assoc($userIdResult);
    $userId = $row['ID'];

    $dateDepart = $_POST['dateDepart'];
    $dateRetour = $_POST['dateRetour'];

    if (empty($dateDepart) || empty($dateRetour)) {
        $response = array(
            'success' => false,
            'message' => "Veuillez entrer des dates valides."
        );
        echo json_encode($response);
        exit();
    }

    var_dump($userId, $vehicleId, $dateDepart, $dateRetour);

    $query = "INSERT INTO reservation (user_id, vehicle_id, date_depart, date_retour, date_location) VALUES ($userId, $vehicleId, '$dateDepart', '$dateRetour', NOW())";

    $result = mysqli_query($conn, $query);

    if (mysqli_error($conn)) {
        echo mysqli_error($conn);
        exit();
    }

    if ($result) {
        $response = array(
            'success' => true,
            'message' => "Réservation confirmée avec succès."
        );
    } else {
        $response = array(
            'success' => false,
            'message' => "Une erreur s'est produite lors de la réservation: " . mysqli_error($conn)
        );
    }
    echo json_encode($response);
    exit();
}
