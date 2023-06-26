<?php
session_start();
?>

<?php include "../templates/header.php" ?>

<h3 class="text-center my-3">Véhicules disponibles</h3>

<div class="d-flex justify-content-center mt-3">
    <div class="table-responsive w-75">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="sortable" data-column="Car Make">Marque</th>
                <th class="sortable" data-column="Car Model">Modèle</th>
                <th class="sortable" data-column="Engine Size">Motorisation</th>
                <th class="sortable" data-column="Horsepower">Puissance</th>
                <th class="sortable" data-column="Torque">Couple</th>
                <th class="sortable" data-column="Year">Année</th>
                <th class="sortable" data-column="Price">Prix</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="align-middle">
            <?php
            $conn = mysqli_connect('localhost', 'root');
            mysqli_select_db($conn, 'cars');

            // Paramètres de pagination
            $itemsPerPage = 50;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($currentPage - 1) * $itemsPerPage;

            // Requête avec pagination
            $query = "SELECT * FROM sport_cars LIMIT $offset, $itemsPerPage";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID'];
                $marque = $row['Car Make'];
                $modele = $row['Car Model'];
                $motorisation = $row['Engine Size'];
                $puissance = $row['Horsepower'];
                $annee = $row['Year'];
                $couple = $row['Torque'];
                $prix = $row['Price'];

                echo "<tr>";
                echo "<td>$marque</td>";
                echo "<td>$modele</td>";
                echo "<td>$motorisation</td>";
                echo "<td>$puissance</td>";
                echo "<td>$couple</td>";
                echo "<td>$annee</td>";
                echo "<td>$prix</td>";
                if (!isset($_SESSION['username'])) {
                    echo "<td><a href='login.php' class='btn btn-outline-success' title='Réserver'><i class='bi bi-calendar-check'></i></a></td>";
                } else {
                    // L'utilisateur est connecté, afficher le bouton de réservation vers reservation.php
                    echo "<td><a href='reservation.php?id=$id' class='btn btn-outline-success'><i class='bi bi-calendar-check'></i></a></td>";
                }
                echo "</tr>";
            }

            mysqli_close($conn);
            ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($currentPage - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php
                // Calcul du nombre total de pages
                $conn = mysqli_connect('localhost', 'root');
                mysqli_select_db($conn, 'cars');
                $totalCountQuery = "SELECT COUNT(*) AS total FROM cars_dataset";
                $totalCountResult = mysqli_query($conn, $totalCountQuery);
                $totalCountRow = mysqli_fetch_assoc($totalCountResult);
                $totalCount = $totalCountRow['total'];
                $totalPages = ceil($totalCount / $itemsPerPage);

                $maxVisiblePages = 5; // Nombre maximum d'onglets visibles

                // Calcul du début et de la fin de la plage des onglets
                $startPage = max(1, $currentPage - floor($maxVisiblePages / 2));
                $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

                // Affichage des liens de pagination
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $active = ($i == $currentPage) ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                }

                mysqli_close($conn);
                ?>
                <li class="page-item <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo ($currentPage + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</div>

<?php include "../templates/footer.php" ?>
