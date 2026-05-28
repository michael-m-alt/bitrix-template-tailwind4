<?php
/**
 * Точка входа для кастомного кода проекта
 * Подключается в /bitrix/php_interface/dbconn.php или /local/php_interface/dbconn.php
 */

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Local\Starter\Config;

defined('B_PROLOG_INCLUDED') || die();

// Автозагрузка классов из /local/php_interface/lib/
if (file_exists(__DIR__ . '/lib')) {
    spl_autoload_register(function ($class) {
        $prefix = 'Local\\';
        $baseDir = __DIR__ . '/lib/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    });
}

// Инициализация конфигурации проекта
Config::getInstance();

// Подключение языковых файлов для текущего сайта
$siteId = SITE_ID;
$langFile = __DIR__ . "/lang/{$siteId}/init.php";
if (file_exists($langFile)) {
    include $langFile;
}

// Регистрация обработчиков событий (пример)
$eventManager = EventManager::getInstance();

// Пример: обработка перед подключением шаблона
$eventManager->addEventHandler(
    'main',
    'OnBeforeProlog',
    ['\Local\Starter\Events', 'onBeforeProlog']
);

// Логирование ошибок в development режиме
if (defined('STARTER_DEV_MODE') && STARTER_DEV_MODE === true) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
