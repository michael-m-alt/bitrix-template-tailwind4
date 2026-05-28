<?php
/**
 * Файл установки модуля kbnet.starter
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;

class kbnet_starter extends CModule
{
    /**
     * Идентификатор модуля
     */
    public $MODULE_ID = 'kbnet.starter';
    
    /**
     * Версия модуля
     */
    public $MODULE_VERSION;
    
    /**
     * Дата версии
     */
    public $MODULE_VERSION_DATE;
    
    /**
     * Название модуля
     */
    public $MODULE_NAME;
    
    /**
     * Описание модуля
     */
    public $MODULE_DESCRIPTION;
    
    /**
     * Партнер
     */
    public $PARTNER_NAME;
    
    /**
     * Сайт партнера
     */
    public $PARTNER_URI;
    
    /**
     * Конструктор модуля
     */
    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');
        
        $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME         = 'Студия K.B.Net - Стартовый модуль';
        $this->MODULE_DESCRIPTION  = 'Базовый модуль для ускорения разработки проектов на 1С-Битрикс. Включает ORM для настроек, AJAX контроллер и шаблон сайта.';
        $this->PARTNER_NAME        = 'Студия K.B.Net';
        $this->PARTNER_URI         = 'https://www.kbnet.ru';
        
        $this->groupID = 'kbnet';
    }

    /**
     * Установка модуля
     */
    public function DoInstall(): void
    {
        global $APPLICATION, $DB;
        
        // Проверка зависимостей
        if (!Loader::includeModule('iblock')) {
            $APPLICATION->ThrowException('Требуется установленный модуль "Инфоблоки"');
            return;
        }

        // Сначала регистрируем модуль в системе
        ModuleManager::registerModule($this->MODULE_ID);
        
        // Создаем таблицу настроек через SQL (до подключения классов модуля)
        $sqlFile = __DIR__ . '/db/mysql/install.sql';
        if (file_exists($sqlFile)) {
            $sqlContent = file_get_contents($sqlFile);
            $queries = array_filter(array_map('trim', explode(';', $sqlContent)));
            foreach ($queries as $query) {
                if (!empty($query)) {
                    $DB->Query($query);
                }
            }
        }
        
        // Вызов хука установки
        \Kbnet\Starter\Starter::onModuleInstall();

        $APPLICATION->IncludeAdminFile(
            GetMessage('KBNET_STARTER_INSTALL_TITLE'),
            __DIR__ . '/step.php'
        );
    }

    /**
     * Удаление модуля
     */
    public function DoUninstall(): void
    {
        global $APPLICATION, $DB, $step;
        
        $step = (int)$step;
        
        if ($step < 2) {
            // Показываем форму подтверждения удаления таблиц
            $APPLICATION->IncludeAdminFile(
                GetMessage('KBNET_STARTER_UNINSTALL_TITLE'),
                __DIR__ . '/unstep1.php'
            );
            return;
        }

        // Удаление таблиц если подтверждено
        if (isset($_REQUEST['delete_tables']) && $_REQUEST['delete_tables'] === 'Y') {
            try {
                $DB->Query("DROP TABLE IF EXISTS b_kbnet_starter_settings");
            } catch (\Exception $e) {
                // Таблица может не существовать
            }
        }

        // Вызов хука удаления
        \Kbnet\Starter\Starter::onModuleUninstall();

        // Снятие с регистрации
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            GetMessage('KBNET_STARTER_UNINSTALL_COMPLETE'),
            __DIR__ . '/unstep2.php'
        );
    }
}
