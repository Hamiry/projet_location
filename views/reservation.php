<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion ou afficher un message d'erreur approprié
    header("Location: login.php");
    exit;
}

// On établit la connexion avec la base de données
$conn = mysqli_connect('localhost', 'root', '', 'cars');

// Récupérer l'ID du véhicule depuis l'URL
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    // Récupérer les informations du véhicule depuis la base de données
    $query = "SELECT * FROM sport_cars WHERE ID = '$vehicleId'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $marque = $row['Car Make'];
        $modele = $row['Car Model'];
        $moteur = $row['Engine Size'];
        $puissance = $row['Horsepower'];
        $couple = $row['Torque'];
        $annee = $row['Year'];
        $prix = $row['Price'];
    }
}
?>

<?php include '../templates/header.php' ?>

    <div class="container w-50 mt-3">
        <div class="card text-center shadow">
            <div class="card-header">
                Informations sur le véhicule
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $marque . ' ' . $modele; ?></h5>
                <p class="card-text m-0"><span>Motorisation : </span><?php echo $moteur . "L"; ?></p>
                <p class="card-text m-0"><span>Puissance : </span><?php echo $puissance . " ch"; ?></p>
                <p class="card-text m-0"><span>Couple : </span><?php echo $couple . " Nm"; ?></p>
                <p class="card-text m-0"><span>Année : </span><?php echo $annee; ?></p>
                <p class="card-text m-0"><span>Prix : </span><?php echo $prix . "€"; ?></p>
            </div>
        </div>
    </div>

    <div class="container mt-4 w-25">
        <form action="../controllers/ReservationController.php" method="post" id="reservationForm">
            <div class="row">
                <div class="col-md-6 text-center">
                    <div class="form-group my-2">
                        <label for="dateDepart" class="form-label m-0">Date départ</label>
                        <input type="date" class="form-control" id="dateDepart" name="dateDepart">
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="form-group my-2">
                        <label for="dateRetour" class="form-label m-0">Date retour</label>
                        <input type="date" class="form-control" id="dateRetour" name="dateRetour">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
        </form>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Action réussie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="closeModalButton">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#reservationForm').submit(function (e) {
                e.preventDefault();

                var dateDepart = $('input[name="dateDepart"]').val();
                var dateRetour = $('input[name="dateRetour"]').val();

                $.ajax({
                    url: "../controllers/ReservationController.php",
                    type: "POST",
                    data: $('#reservationForm').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success === false) {
                            $('#successMessage').text(response.message);
                            $('#successModal').modal('show');
                        } else {
                            // Redirection vers une autre page ou autre action réussie
                            window.location.href = "../views/index.php";
                        }
                    },
                    error: function (xhr, status, error) {
                        var errorMessage = "Une erreur s'est produite lors de la réservation.";
                        $('#successMessage').text(errorMessage);
                        $('#successModal').modal('show');
                    }
                });
            });

            $('#closeModalButton').on('click', function () {
                window.location.href = "../views/index.php";
            });
        });
    </script>

<?php include "../templates/footer.php" ?>