<?php
/**
 * Главный класс модуля kbnet.starter
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

namespace Kbnet\Starter;

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

class Starter
{
    /**
     * Идентификатор модуля
     */
    const MODULE_ID = 'kbnet.starter';

    /**
     * Обработчик события OnModuleInstall
     * Вызывается при установке модуля
     */
    public static function onModuleInstall(): void
    {
        // Регистрация обработчиков событий
        self::registerEvents();
        
        // Создание таблицы настроек через ORM
        self::createSettingsTable();
    }

    /**
     * Обработчик события OnModuleUninstall
     * Вызывается при удалении модуля
     */
    public static function onModuleUninstall(): void
    {
        // Удаление обработчиков событий
        self::unregisterEvents();
        
        // Таблица будет удалена через install.sql при подтверждении
    }

    /**
     * Регистрация обработчиков событий
     */
    private static function registerEvents(): void
    {
        $eventManager = EventManager::getInstance();
        
        // Пример регистрации события (можно расширить)
        // $eventManager->addEventHandler(
        //     'main',
        //     'OnBeforeProlog',
        //     [__NAMESPACE__ . '\\Events', 'onBeforeProlog']
        // );
    }

    /**
     * unregisterEvents
     */
    private static function unregisterEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unregisterEventHandler(self::MODULE_ID);
    }

    /**
     * Создание таблицы настроек через ORM
     * Таблица создается автоматически при первом обращении к Entity
     */
    private static function createSettingsTable(): void
    {
        // Явно создаем таблицу через ORM
        try {
            \Kbnet\Starter\ORM\SettingsTable::createTable();
        } catch (\Exception $e) {
            // Таблица может уже существовать, это нормально
            // Игнорируем ошибку, если таблица уже создана
        }
    }
}
