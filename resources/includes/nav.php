<nav>
    <?php
    if (!isset($_SESSION["role"])) {
    ?>
        <a href="/">Iniciar sesión</a>
        <a href="/signin">Registrarse</a>
        <?php
    } else {

        if ($_SESSION["role"] === "admin") {
        ?>
            <a href="/adminpanel">Panel de Administrador</a>
        <?php

        }
        ?>
        <a href="/logout">Cerrar Sesion</a>
    <?php
    } ?>
</nav>