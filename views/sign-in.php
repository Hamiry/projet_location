<?php include '../templates/header.php' ?>

<h1 class="text-center m-3">Inscription</h1>
<div class="content">
    <form action="../controllers/SignInController.php" method="post" id="inscriptionForm" class="shadow p-4 rounded w-25">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" class="form-control" required>
            <div class="error-message-username text-danger text-center"></div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-2">S'inscrire</button>
        </div>
    </form>
</div>
<p class="text-center mt-3">Déjà un compte ? <a href="login.php">Se connecter</a></p>

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
        $('#inscriptionForm').submit(function (e) {
            e.preventDefault();

            var username = $('input[name="username"]').val();
            var password = $('input[name="password"]').val();

            $('.error-message-username').empty();

            $.ajax({
                url: "../controllers/SignInController.php",
                type: "POST",
                data: $('#inscriptionForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success === false) {
                        if (response.message.includes("déjà utilisé")) {
                            $('.error-message-username').text(response.message);
                        } else {
                            $('.error-message-username').text(response.message);
                        }
                    } else {
                        $('#successMessage').text(response.message);
                        $('#successModal').modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('#closeModalButton').on('click', function () {
            window.location.href = "../views/login.php";
        });
    });
</script>

<?php include '../templates/footer.php' ?>
