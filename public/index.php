<?php

declare(strict_types=1);
require __DIR__ . '/../resources/includes/utilities.php';
session_set_cookie_params([
    'httponly' => true,
    'secure' => false, // se necesita https
    'path' => '/',
    'lifetime' => 3600     // Duracion en segundos
]);

session_start();
if (isset($_SESSION['expired_time'])) {
    if ($_SESSION['expired_time'] < time()) {
        logout();
    } else {
        $_SESSION['expired_time'] = time() + 3600;
       
    }
}
?>


<!DOCTYPE html>
<!-- Incluimos el archivo web.php que contiene las rutas -->
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..'
    . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';;
?>