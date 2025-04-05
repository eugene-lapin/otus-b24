<?php

use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();
$method = $request->getPost('_method') ?? $request->getRequestMethod();
$url = $request->getQuery('url');

if (!$url) {
    $action = 'index';
} else {
    $parts = explode('/', $url);
    if (!in_array($parts[0], ['add', 'edit', 'newproc'])) {
        $action = 'index';
        $item = $parts[0];
    } else {
        $action = $parts[0];
        $item = $parts[1] ?? null;
    }
}

$controller = new Otus\Controllers\Doctors\Controller();

switch ($action) {
    case 'index':
        if ($method == 'GET')
            if ($item) {
                $controller->show($item);
            } else {
                $controller->index();
            }
        if ($method == 'POST') {
            try {
                $entity = $request->getPost('entity');
                if ($entity == 'doctor') {
                    $controller->create();
                } elseif ($entity == 'proc') {
                    $controller->createProc();
                } else {
                    throw new \Exception("Unknown entity");
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        if ($method == 'PUT') {
            if ($request->getPost('ID')) {
                try {
                    $controller->update();
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        break;
    case 'add':
        $controller->add();
        break;
    case 'edit':
        $controller->edit($item);
        break;
    case 'newproc':
        $controller->addProc();
        break;
    default:
        echo '404 Not Found';
        break;
}

