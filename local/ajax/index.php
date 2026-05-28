<?php
/**
 * AJAX роутер для обработки запросов
 * Точка входа: /local/ajax/index.php
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\Response\Json;
use Local\Starter\Controllers\Ajax;

define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NOT_CHECK_PERMISSIONS', false);
define('DisableEventsCheck', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json');

try {
    if (!Loader::includeModule('iblock')) {
        throw new \Exception('Iblock module not installed');
    }
    
    // Проверка сессии для всех POST запросов
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!check_bitrix_sessid()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid session ID',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // Получение действия из запроса
    $request = Context::getCurrent()->getRequest();
    $action = $request->get('action') ?? $request->get('ACTION') ?? '';
    
    if (empty($action)) {
        // Попытка получить из JSON тела
        $input = file_get_contents('php://input');
        $jsonData = json_decode($input, true);
        $action = $jsonData['action'] ?? '';
    }
    
    if (empty($action)) {
        throw new \Exception('Action parameter is required');
    }
    
    // Создание экземпляра контроллера и вызов действия
    $controller = new Ajax();
    $controller->setActionName($action . 'Action');
    
    // Выполнение действия
    $response = $controller->run();
    
    if ($response instanceof Json) {
        echo $response->getData();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid response type',
        ], JSON_UNESCAPED_UNICODE);
    }
    
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => \Local\Starter\Config::getInstance()->isDevMode() ? $e->getTraceAsString() : null,
    ], JSON_UNESCAPED_UNICODE);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
