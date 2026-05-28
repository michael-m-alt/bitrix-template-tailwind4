<?php
/**
 * Обработчики событий проекта
 */

namespace Local\Starter;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

class Events
{
    /**
     * Обработчик события OnBeforeProlog
     * Подключение глобальных ассетов, инициализация
     */
    public static function onBeforeProlog(): void
    {
        // Подключение глобальных CSS
        Asset::getInstance()->addCss('/local/templates/.default/styles.css');
        
        // Подключение глобальных JS
        Asset::getInstance()->addJs('/local/templates/.default/script.js');
        
        // Добавление мета-тегов по умолчанию
        global $APPLICATION;
        
        // Пример: добавление viewport для мобильных
        $APPLICATION->SetPageProperty('viewport', 'width=device-width, initial-scale=1.0');
    }
}
