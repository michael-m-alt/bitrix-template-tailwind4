<?php
/**
 * Компонент starter:base.info
 * Демонстрационный компонент с кешированием и AJAX тестом
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

namespace Local\Components\Starter;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use CBitrixComponent;
use Kbnet\Starter\Config;

Loc::loadMessages(__FILE__);

class BaseInfo extends CBitrixComponent
{
    /**
     * Проверка подключения модулей
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME'] = (int)($arParams['CACHE_TIME'] ?? 3600);
        $arParams['AJAX_TEST'] = $arParams['AJAX_TEST'] ?? 'Y';
        
        return $arParams;
    }

    /**
     * Выполнение компонента
     */
    public function executeComponent(): void
    {
        if (!Loader::includeModule('iblock')) {
            ShowError(Loc::getMessage('KBNET_STARTER_IBLOCK_REQUIRED'));
            return;
        }

        // Подключение стилей компонента
        Asset::getInstance()->addCss($this->getTemplate()->GetPath() . '/style.css');
        Asset::getInstance()->addJs($this->getTemplate()->GetPath() . '/script.js');

        // Получение данных для отображения
        $this->arResult = [
            'SITE_NAME' => Config::getInstance()->get('SITE_NAME', 'K.B.Net'),
            'SITE_DESCRIPTION' => Config::getInstance()->get('SITE_DESCRIPTION', 'Стартовый сайт на Bitrix'),
            'TIMESTAMP' => date('d.m.Y H:i:s'),
            'AJAX_TEST_ENABLED' => $this->arParams['AJAX_TEST'] === 'Y'
        ];

        $this->includeComponentTemplate();
    }

    /**
     * Настройка действий контроллера для AJAX
     */
    public function configureActions(): array
    {
        return [];
    }
}
