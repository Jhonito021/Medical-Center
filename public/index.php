<?php
session_start();
require_once __DIR__ . '/../config/database.php';

spl_autoload_register(function ($class) {
    foreach ([__DIR__ . '/../controllers/', __DIR__ . '/../models/'] as $path) {
        $file = $path . $class .'.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$parts = $url === '' ? ['home'] : explode('/', filter_var($url, FILTER_SANIIZE_URL));

$resource = $parts[0] ?? 'home';
$id = isset($parts[1]) && is_numeric($parts[1]) ? (int)$parts[1] : null;

$action = null;

if ($id !== null && isset($parts[2])) {
    $action = $parts[2];
} elseif ($id === null && isset($parts[1])) {
    $action = $parts[1];
}

$controllerMap = [
    'home' => 'HomeController',
    'patients' => 'PatientController',
    'medecine' => 'MedecinController',
    'consulations' => 'ConsulationControlles',
];

$controllerName = $controllerMap[$resource] ?? null;

if (!=$controllerName || !class_exists($controllerName)) {
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
