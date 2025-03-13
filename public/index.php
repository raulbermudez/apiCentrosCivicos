<?php
require "../bootstrap.php";
require_once "../vendor/autoload.php";

use App\Core\Router;
use App\Controllers\UsuariosController;
use App\Controllers\CentrosController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Ponemos las cabeceras
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization, X-Auth-Token, HTTP_AUTHORIZATION");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE"); 

// Obtener método de la solicitud
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == "OPTIONS") {
    http_response_code(200);
    exit();
}

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Configuración de rutas
$router = new Router();
// ******** USUARIOS **********
$router->add(array(
    "name" => "usuarios",
    "path" => "/^\/user$/",
    "action" => UsuariosController::class,
    "section" => "private"
));

$router->add(array(
    "name" => "login",
    "path" => "/^\/login$/",
    "action" => UsuariosController::class,
    "section" => "public"
));

$router->add(array(
    "name" => "Registro de usuario",
    "path" => "/^\/register$/",
    "action" => UsuariosController::class,
    "section" => "public"
));

$router->add(array(
    "name" => "Refrescar token",
    "path" => "/^\/token\/refresh$/",
    "action" => UsuariosController::class,
    "section" => "public"
));

// ******** CENTROS CIVICOS **********
$router->add(array(
    "name" => "centros",
    "path" => "/^\/centros$/",
    "action" => CentrosController::class,
    "section" => "public"
));

$router->add(array(
    "name" => "Centro especifico",
    "path" => "/^\/centros\/([0-9]+)?$/",
    "action" => CentrosController::class,
    "section" => "public"
));

// ******** ACTIVIDADES **********
$router->add(array(
    "name" => "Actividades con posibilidad de filtro",
    "path" => "/^\/actividades$/",
    "action" => CentrosController::class,
    "section" => "public"
));

$router->add(array(
    "name" => "Actividades de centro especifico",
    "path" => "/^\/centros\/([0-9]+)?\/actividades$/",
    "action" => CentrosController::class,
    "section" => "public"
));

// ******** INSTALACIONES **********
$router->add(array(
    "name" => "Instalaciones de centro especifico",
    "path" => "/^\/centros\/([0-9]+)?\/instalaciones$/",
    "action" => CentrosController::class,
    "section" => "public"
));

$router->add(array(
    "name" => "Instalaciones con posibilidad de filtro",
    "path" => "/^\/instalaciones$/",
    "action" => CentrosController::class,
    "section" => "public"
));

// ******** RESERVAS **********

$router->add(array(
    "name" => "Reservas de usuario",
    "path" => "/^\/reservas$/",
    "action" => UsuariosController::class,
    "section" => "private"
));

$router->add(array(
    "name" => "Eliminar",
    "path" => "/^\/reservas\/([0-9]+)?$/",
    "action" => UsuariosController::class,
    "section" => "private"
));

// ********** Inscripciones **********
$router->add(array(
    "name" => "Reservas de usuario",
    "path" => "/^\/inscripciones$/",
    "action" => UsuariosController::class,
    "section" => "private"
));

$router->add(array(
    "name" => "Eliminar",
    "path" => "/^\/inscripciones\/([0-9]+)?$/",
    "action" => UsuariosController::class,
    "section" => "private"
));

// ********** FIN DE RUTAS **********


// Buscar la ruta
$route = $router->match($request_uri);
if ($route) {
    $controllerName = $route['action'];
    // Si la ruta es privada, se pasa el userId
    if ($route['section'] == "private") {
        // Solo validamos el token si la función de la API es privada

        // Obtener cabecera de autorización
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? null;


        // Validar si hay token antes de intentar procesarlo
        $jwt = null;
        if ($authHeader) {
            $arr = explode(" ", $authHeader);
            if (count($arr) == 2) {
                $jwt = $arr[1];
            }
        }

        // Validar JWT
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));

            } catch (Exception $e) {
                echo json_encode([
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                ]);
                exit(http_response_code(401));
            }
        } else {
            echo json_encode([
                "message" => "Access denied."
            ]);
            exit(http_response_code(401));
        }
        $controller = new $controllerName($request_method, $jwt);
    } else {
        $controller = new $controllerName($request_method);
    }
    $controller->processRequest();
} else {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
}
