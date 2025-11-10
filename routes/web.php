<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Recibe la request de la url


// Lista blanca de rutas permitidas
$allowed_routes = [ // En esta lista relacionamos posibles cadenas del request con el enlace real
    '/' => 'home.php',
    '/adminpanel' => 'adminpanel.php',
    '/signin' => 'signin.php',
    '/welcome' => 'welcome.php',
    '/logout' => 'logout.php'
];


if (array_key_exists($request, $allowed_routes)) { // Aqui comprobamos que el request este dentro de las keys añadidas anteriormente
    require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources'
        . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $allowed_routes[$request];
    //Si la encuentra, le mandara a la pagina 
} else {
    // Si no la encuentra dentro de nuestro array, entonces le manda a la pagina de error
    http_response_code(404);
    require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources'
        . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . '404.php';
}
