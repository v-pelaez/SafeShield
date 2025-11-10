<?php

if (isset($_POST['accept_cookies'])) {
    setcookie('functional_cookies', 'true', time() + (30 * 24 * 60 * 60), '/');
    $_SESSION["functional_cookies"] = true;
    header("Location: /");
    exit();
} else if (isset($_POST['cancel_cookies'])) {
    unset($_SESSION);
    header("Location: /");
    exit();
}

$consentGiven = $_SESSION["functional_cookies"] ?? ($_COOKIE['functional_cookies'] ?? false);

if (!$consentGiven) { ?>
    <div id="cookiewall">
        <div>
            <h1>Uso de cookies 🍪</h1>
        <p>Este sitio web utiliza únicamente cookies funcionales, esenciales para el correcto funcionamiento de la página,
             mantener tu sesión iniciada y guardar tus preferencias de configuración.
             Estas cookies permiten mejorar tu experiencia de navegación. <br>
             </p><span> ¿Aceptas el uso de estas cookies? </span>
        <form method="post" action="/cookies">
            <button type="submit" name="accept_cookies">Aceptar cookies</button>
            <button type="submit" name="cancel_cookies">Rechazar</button>
        </form>
    <?php } ?>
    </div></div>