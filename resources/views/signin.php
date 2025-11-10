<?php require __DIR__ . '/../includes/head.php'; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
    <?php
    if (isset($_SESSION["role"])) {
        header("Location: /welcome");
    } ?>
    <div id="log-box">
        <div id="logo-form">
            <img src="src/seguridad-cibernetica.png" alt="" />
            <h1>SafeShield</h1>
        </div>
        <form action="/signin" method="post">
            <label for="user"><img src="src/user.png" alt="">Usuario:</label>
            <input type="text" name="user" id="user" placeholder="usuario">
            <label for="password"><img src="src/lock.png" alt="">Contraseña:</label>
            <input type="password" name="password" id="password" placeholder="*********">
            <label for="password"><img src="src/lock.png" alt="">Confirme su contraseña:</label>
            <input type="password" name="passconfirm" id="passconfirm" placeholder="*********">
            <div id="roleselector">
                <label for="remeber"> Tipo de cuenta: </label>
                <select name="role" id="role">
                    <option value="guest">Invitado</option>
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                $errores = checkNewUser();
                if (empty($errores)) {
                    $newUser = [
                        "user" => $_POST["user"],
                        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
                        "role" => $_POST["role"]
                    ];
                    signin($newUser);
                } else {
                    printErrorList($errores);
                }
            }
            ?>
            <div id="submits">
                <button type="submit" name="signin" id="signin">Registrar</button>
            </div>
        </form>
        <a href="/">Ya tengo una cuenta</a>
    </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>