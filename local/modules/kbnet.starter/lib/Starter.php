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
        // ORM сама создаст таблицу при первом запросе к SettingsTable
        // Явное создание не требуется, но можно форсировать
        try {
            $connection = \Bitrix\Main\Application::getConnection();
            $connection->queryScalar("SELECT 1 FROM b_kbnet_starter_settings LIMIT 1");
        } catch (\Exception $e) {
            // Таблица еще не создана, ORM создаст её автоматически
            // при первом обращении к SettingsTable::getList()
        }
    }
}
