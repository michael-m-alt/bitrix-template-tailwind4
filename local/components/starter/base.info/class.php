<?php
/**
 * Компонент: Базовая информация (Starter Base Info)
 * Демонстрационный компонент на D7 с кешированием и AJAX
 */

namespace Local\Components\Starter;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CBitrixComponent;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Diag\Debug;

Loc::loadMessages(__FILE__);

class BaseInfo extends CBitrixComponent
{
    /**
     * Проверка прав и зависимостей
     * @return bool
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TYPE'] = $arParams['CACHE_TYPE'] ?? 'A';
        $arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ?? 3600;
        $arParams['TEXT'] = trim($arParams['TEXT'] ?? '');
        
        return $arParams;
    }
    
    /**
     * Выполнение компонента
     */
    public function executeComponent(): void
    {
        if (!Loader::includeModule('iblock')) {
            ShowError(Loc::getMessage('STARTER_IBLOCK_MODULE_NOT_INSTALLED'));
            return;
        }
        
        try {
            // Получение данных с кешированием
            $this->arResult = $this->getData();
            
            $this->includeComponentTemplate();
        } catch (\Exception $e) {
            Debug::writeToFile("BaseInfo component error: " . $e->getMessage(), '', 'starter_errors.log');
            
            if ($this->arParams['SET_STATUS_404'] === 'Y') {
                global $APPLICATION;
                $APPLICATION->SetStatus(404);
            }
            
            ShowError(Loc::getMessage('STARTER_COMPONENT_ERROR'));
        }
    }
    
    /**
     * Получение данных для компонента
     * Используется тегированный кеш для iblock-зависимых данных
     * 
     * @return array
     */
    private function getData(): array
    {
        $cache = Cache::createInstance();
        $cacheId = 'starter_base_info_' . md5(serialize($this->arParams));
        $cacheDir = '/starter/base_info';
        $cacheTtl = $this->arParams['CACHE_TIME'];
        
        // Теги для сброса кеша при изменении инфоблоков
        $taggedCacheId = 'starter_base_info';
        
        if ($this->arParams['CACHE_TYPE'] === 'A' || $this->arParams['CACHE_TYPE'] === 'Y') {
            if ($cache->initCache($cacheTtl, $cacheId, $cacheDir)) {
                return $cache->getVars();
            }
            
            $cache->startDataCache();
            
            try {
                // Формирование данных
                $result = [
                    'text' => $this->arParams['TEXT'],
                    'siteName' => \COption::GetOptionString('main', 'site_name', 'My Site'),
                    'serverTime' => date('d.m.Y H:i:s'),
                    'language' => LANGUAGE_ID,
                ];
                
                // Пример работы с ORM (если нужно получить данные из инфоблока)
                /*
                use Bitrix\Iblock\ORM\ElementTable;
                
                $elements = ElementTable::getList([
                    'filter' => [
                        '=IBLOCK_ID' => $this->arParams['IBLOCK_ID'] ?? 1,
                        '=ACTIVE' => 'Y',
                    ],
                    'select' => ['ID', 'NAME', 'CODE'],
                    'limit' => 5,
                    'order' => ['SORT' => 'ASC', 'ID' => 'DESC'],
                ])->fetchAll();
                
                $result['elements'] = $elements;
                */
                
                $cache->endDataCache($result);
                
                // Регистрация тегов для автоматического сброса кеша
                if (defined('BX_COMP_MANAGED_CACHE')) {
                    global $CACHE_MANAGER;
                    $CACHE_MANAGER->RegisterTag($taggedCacheId);
                }
                
                return $result;
            } catch (\Exception $e) {
                $cache->abortDataCache();
                throw $e;
            }
        }
        
        // Без кеширования
        return [
            'text' => $this->arParams['TEXT'],
            'siteName' => \COption::GetOptionString('main', 'site_name', 'My Site'),
            'serverTime' => date('d.m.Y H:i:s'),
            'language' => LANGUAGE_ID,
        ];
    }
}
