<?php
session_start();
require_once __DIR__ . '/../config/database.php';

spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/../controllers/', __DIR__ . '/../models/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$url = '';
if (isset($_GET['url'])) {
    $url = rtrim($_GET['url'], '/');
}

$parts = explode('/', $url);
$resource = 'home';
if (!empty($parts[0])) {
    $resource = $parts[0];
}

$id = null;
if (isset($parts[1]) && is_numeric($parts[1])) {
    $id = (int)$parts[1];
}

$action = null;
if (isset($parts[1]) && !is_numeric($parts[1])) {
    $action = $parts[1];
}
if (isset($parts[2])) {
    $action = $parts[2];
}

$map = array(
    'home'          => 'HomeController',
    'patients'      => 'PatientController',
    'medecins'      => 'MedecinController',
    'consultations' => 'ConsultationController'
);

$controllerName = null;
if (isset($map[$resource])) {
    $controllerName = $map[$resource];
}

if ($controllerName === null || !class_exists($controllerName)) {
    http_response_code(404);
    require_once __DIR__ . '/../views/errors/404.php';
    exit;
}

$controller = new $controllerName();

if ($id !== null) {
    if ($action === 'edit') {
        $controller->edit($id);
    } elseif ($action === 'delete') {
        $controller->delete($id);
    } else {
        $controller->show($id);
    }
} elseif ($action !== null) {
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
    }
} else {
    $controller->index();
}