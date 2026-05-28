<?php
/**
 * Файл подключения модуля kbnet.starter
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;

// Автозагрузка классов модуля
Loader::registerAutoLoadClasses(
    'kbnet.starter',
    [
        // Главный класс модуля
        'Kbnet\\Starter\\Starter' => '/lib/Starter.php',
        
        // Конфигурация
        'Kbnet\\Starter\\Config' => '/lib/Starter/Config.php',
        
        // ORM сущности
        'Kbnet\\Starter\\ORM\\SettingsTable' => '/lib/Starter/ORM/SettingsTable.php',
        
        // Контроллеры
        'Kbnet\\Starter\\Controllers\\Ajax' => '/lib/Starter/Controllers/Ajax.php',
    ]
);

// Регистрация обработчиков событий (если нужно)
// Можно использовать вместо events.php для динамической регистрации
