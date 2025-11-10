<?php
session_start();

require_once __DIR__ . '/../app/services/CSRFTokenService.php';

$controllerName = $_GET['controller'] ?? 'movie';
$action = $_GET['action'] ?? 'index';

$controllerClass = ucfirst(strtolower($controllerName)) . 'Controller';
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Controller not found.";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "Controller class not found.";
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "Action not found.";
    exit;
}

$controller->$action();
