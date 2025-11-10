<?php require __DIR__ . '/../includes/head.php'; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>

<main>
    <?php
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        header("Location: /");
    }

    if (!hash_equals($_SESSION['csrf_token'], $_COOKIE['csrf_token'])) {
        logout();
    }



    ?>
    <div id="log-box">
        <div id="logo-form">
            <h2>Actualizar información</h1>
        </div>
        <form action="/adminpanel" method="post">
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" id="username" placeholder=<?php echo $_SESSION["user"] ?>>
            <div id="roleselector">
                <label for="role">Rol de usuario:</label>
                <select name="role" id="role">
                    <option value="guest" <?php if ($_SESSION["role"] === "guest") echo "selected"; ?>>Invitado</option>
                    <option value="user" <?php if ($_SESSION["role"] === "user") echo "selected"; ?>>Usuario</option>
                    <option value="admin" <?php if ($_SESSION["role"] === "admin") echo "selected"; ?>>Administrador</option>
                </select>
            </div>
            <div id="colorpickers">
                <div>
                    <label for="header-color">Color de cabecera:</label>
                    <input type="color" name="header-color" id="header-color" value="<?= htmlspecialchars($headerColor) ?>">
                </div>
                <div>
                    <label for="bg-color">Color de fondo:</label>
                    <input type="color" name="bg-color" id="bg-color" value="<?= htmlspecialchars($bgColor) ?>">
                </div>
                <div>
                    <label for="footer-color">Color de pie:</label>
                    <input type="color" name="footer-color" id="footer-color" value="<?= htmlspecialchars($footerColor) ?>">
                </div>
            </div>
            <div id="submits">
                <button type="submit" name="adminupdate" id="adminupdate">Aplicar</button>
            </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $errorLog = checkUpdate();
            if (empty($errorLog)) {
                setcookie($_SESSION['user'] . '_bg-color', $_POST["bg-color"], time() + (30 * 24 * 60 * 60), '/');
                setcookie($_SESSION['user'] . '_header-color', $_POST["header-color"], time() + (30 * 24 * 60 * 60), '/');
                setcookie($_SESSION['user'] . '_footer-color', $_POST["footer-color"], time() + (30 * 24 * 60 * 60), '/');

                updateUser(
                    $_POST["username"] != "" ? $_POST["username"]  : $_SESSION["user"],
                    $_POST["role"] != "" ? $_POST["role"] : $_SESSION["role"]
                );
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                setcookie('csrf_token', $_SESSION['csrf_token'], time() + (60 * 60), "/", "", false, true); //Generamos y guardamos nuevo token csrf
                header("Location: /");
            } else {
                printErrorList($errorLog);
            }
        }
        ?>
    </div>
</main>

<?php require __DIR__ . '/../includes/footer.php'; ?>