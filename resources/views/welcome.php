<?php require __DIR__ . '/../includes/head.php'; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
    <?php
    if (!isset($_SESSION['role'])) {
        header("Location: /");
    } ?>
    <div id="info">

        <h2>Bienvenido/a
            <?php
            echo  $_SESSION["user"]; ?>
        </h2>

    </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>