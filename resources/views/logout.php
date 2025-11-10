<?php require __DIR__ . '/../includes/head.php'; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <div id="log-box">
        <div id="logo-form">
            <img src="src/seguridad-cibernetica.png" alt="" />
            <h1>SafeShield</h1>
        </div>
        <?php
        if (!isset($_SESSION["role"])) {
            header("Location: /");
        } ?>
        <form action="/logout" method="post">
            <label for="logout">¿Desea cerrar la sesión?</label>
            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (isset($_POST['logout'])) {
                    logout();
                }

                if (isset($_POST['cancellogout'])) {
                    header("Location: /welcome");
                    exit();
                }
            }
            ?>
            <div id="submits">
                <button type="submit" name="logout" id="logout">Cerrar Sesion</button>
                <button type="submit" name="cancellogout" id="cancellogout">Cancelar </button>
            </div>
        </form>
    </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>