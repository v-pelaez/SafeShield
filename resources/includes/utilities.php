<?php
/**
 * Lee un archivo JSON y lo convierte en un array.
 *
 * @param string $path Ruta del archivo JSON.
 * @return array Datos decodificados del JSON.
 */
function readJsons(string $path): array
{
    $file = file_get_contents($path);
    return json_decode($file, true);
}

/**
 * Guarda un array como un archivo JSON.
 *
 * @param string $path Ruta donde se guardará el archivo JSON.
 * @param array $data Array de datos que se codificará y guardará.
 * @return void No retorna nada.
 */
function saveJsons(string $path, array $data): void
{
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($path, $jsonData);
}

/**
 * Verifica si un usuario está en la lista y su contraseña es correcta.
 *
 * @param string $path Ruta del archivo JSON con la lista de usuarios.
 * @param array $logedUser Arreglo con clave "user" y "password" del usuario a verificar.
 * @return array|bool Devuelve un arreglo con el usuario y rol si es válido, o false si no.
 */
function checkUser(string $path, array $logedUser): array | bool
{
    $userList = readJsons($path);
    foreach ($userList as $user) {
        if (
            $user["user"] === $logedUser["user"]
            && password_verify($logedUser["password"], $user["password"])
        ) {
            $newUser = [
                "user" => $user["user"],
                "role" => $user["role"]
            ];
            return $newUser;
        }
    }
    return false;
}

/**
 * Maneja el proceso de inicio de sesión del usuario.
 *
 * Lee los datos enviados por POST, verifica el usuario y contraseña,
 * y si son válidos, inicia sesión guardando datos en $_SESSION y cookies.
 *
 * @return void No devuelve nada. Redirige o muestra error.
 */
function login(): void
{
    $user = strtolower(trim($_POST["user"] ?? ""));
    $password = $_POST["password"] ?? "";
    $logedUser = [
        "user" => $user,
        "password" => $password
    ];
    $checkUser = checkUser("../data/user.json", $logedUser);
    if ($checkUser) {
        $_SESSION["user"] = $checkUser['user'];
        $_SESSION["role"] = $checkUser['role'];
        $_SESSION['start_time'] = time();
        $_SESSION['expired_time'] = $_SESSION['start_time'] + 3600; // La sesion dura una hora
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //Generamos y guardamos token csrf en sesion y cookie
        setcookie('csrf_token', $_SESSION['csrf_token'], time() + (60 * 60), "/", "", false, true);
        if (isset($_POST["remember"])) {
            setcookie("rememberUser", $user, time() + (30 * 24 * 60 * 60), '/');
        }
        header("Location: /welcome");
        exit();
    } else {
        printErrorList(["Ha habido un error en el usuario o contraseña"]);
    }
}

/**
 * Cierra la sesión del usuario.
 *
 * Limpia todas las variables de sesión, destruye la sesión,
 * y redirige al usuario a la página principal.
 *
 * @return void No devuelve nada.
 */
function logout()
{
    session_unset();
    session_destroy();
    header("Location: /");
    exit();
}

/**
 * Agrega un nuevo usuario a la lista y guarda los datos en un archivo JSON.
 *
 * Crea el directorio si no existe, lee los datos actuales,
 * agrega el nuevo usuario, y guarda todo nuevamente.
 *
 * @param array $newData Datos del nuevo usuario a agregar.
 * @return void No devuelve nada, redirige a la página principal.
 */
function signin($newData): void
{
    $path = "../data/user.json";
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    $data = readJsons($path);
    if (!is_array($data)) {
        $data = [];
    }
    $data += array_merge($data, [$newData]);
    saveJsons($path, $data);

    header("Location: /");
}

/**
 * Valida los datos de un nuevo usuario enviados por formulario.
 *
 * Verifica que el nombre de usuario no esté en uso,
 * que el nombre y la contraseña cumplan con un patrón permitido,
 * que las contraseñas coincidan, y que se seleccione un rol válido.
 *
 * @return array Arreglo con mensajes de error. Vacío si no hay errores.
 */
function checkNewUser(): array
{

    $errors = [];
    $newUser = strtolower($_POST["user"] ?? "");
    $userList = readJsons("../data/user.json");
    foreach ($userList as $user) {
        if ($newUser === $user["user"]) {
            $errors[] = "Ese nombre ya esta en uso";
        }
    }
    if (empty($newUser) || is_array($newUser) || !preg_match("/^[a-zA-Z0-9]{3,15}$/", $newUser)) {
        $errors[] = "Introduce un nombre de usuario válido (3-15 caracteres y números).";
    }

    $password = $_POST["password"];
    $passconfirm = $_POST["passconfirm"];
    // Las exigencias de una contraseña segura deberian ser mas estrictas,
    // pero para facilitar la corrección las hemos limitado a que solo sean caracteres y numeros
    if (empty($password) || is_array($password) || !preg_match("/^[a-zA-Z0-9]{3,15}$/", $password)) {
        $errors[] = "Introduce una contraseña válida (3-15 caracteres y números).";
    }
    if ($password != $passconfirm) {
        $errors[] = "Las contraseñas deben ser iguales";
    }

    $roles = ['guest', 'user', 'admin'];
    $roleSelected = $_POST["role"];

    if (!in_array($roleSelected, $roles)) {
        $errors[] = "Seleccione un rol adecuado";
    }

    return $errors;
}

/**
 * Valida los datos para actualizar un usuario.
 *
 * Verifica que el nuevo nombre de usuario no esté en uso
 * (si se ha proporcionado), que el nombre cumpla el patrón,
 * que el rol sea válido, y que los colores sean válidos.
 *
 * @return array Arreglo con mensajes de error. Vacío si no hay errores.
 */
function checkUpdate(): array
{
    $errors = [];


    $newUserName = strtolower($_POST["username"] ?? "");
    $userList = readJsons("../data/user.json");
    foreach ($userList as $user) {
        if ($newUserName === $user["user"]) {
            $errors[] = "Ese nombre ya esta en uso";
        }
    }
    if (!empty($newUserName) && (is_array($newUserName) || !preg_match("/^[a-zA-Z0-9]{3,15}$/", $newUserName))) {
        $errors[] = "Introduce un nombre de usuario válido (3-15 caracteres y números).";
    }

    $roles = ['guest', 'user', 'admin'];
    $roleSelected = $_POST["role"];

    if (!in_array($roleSelected, $roles)) {
        $errors[] = "Seleccione un rol adecuado";
    }

    $bgColor = $_POST["bg-color"];
    $headerColor = $_POST["header-color"];
    $headerColor = $_POST["footer-color"];
    if (!(isValidColor($bgColor) && isValidColor($headerColor) && isValidColor($headerColor))) {
        $errors[] = "Seleccione un color valido";
    }

    return $errors;
}


/**
 * Actualiza el nombre y rol del usuario actualmente en sesión.
 *
 * Busca el usuario en el archivo JSON, modifica sus datos,
 * guarda el archivo actualizado y actualiza $_SESSION.
 *
 * @param string $newUser Nuevo nombre de usuario.
 * @param string $newRole Nuevo rol del usuario.
 * @return bool Devuelve true si se actualizó con éxito, false si no se encontró el usuario.
 */
function updateUser($newUser, $newRole): bool
{
    $userList = readJsons("../data/user.json");
    foreach ($userList as &$user) {   // <--- referencia (&) para modificar
        if ($user["user"] === $_SESSION["user"]) {
            $user["user"] = $newUser;
            $user["role"] = $newRole;
            saveJsons("../data/user.json", $userList);

            $_SESSION["user"] = $newUser;
            $_SESSION["role"] = $newRole;
            unset($user);  // rompo referencia
            return true;
        }
    }
    unset($user);  // prevenir referencia residual si no entra al if
    return false;
}

/**
 * Valida si una cadena es un color hexadecimal válido.
 *
 * Acepta formatos #RRGGBB o #RGB, mayúsculas o minúsculas.
 *
 * @param string $color Cadena con el color a validar.
 * @return bool Devuelve true si es un color válido, false si no.
 */
function isValidColor($color): bool
{
    // Validar formato #RRGGBB o #RGB (mayúsculas o minúsculas)
    return preg_match('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $color);
}

/**
 * Muestra una lista de errores en HTML.
 *
 * Convierte cada mensaje de error en un elemento <li> dentro de un <ul>,
 * todo contenido en un div con clase 'errors'.
 *
 * @param array $errors Arreglo de mensajes de error a mostrar.
 * @return void No devuelve nada.
 */
function printErrorList(array $errors): void
{
    echo "<div class='errors'><ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul></div>";
}
