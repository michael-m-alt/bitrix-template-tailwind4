<?php
/**
 * Класс конфигурации модуля kbnet.starter (Singleton)
 * 
 * @package kbnet.starter
 * @author Студия K.B.Net <www.kbnet.ru>
 */

namespace Kbnet\Starter;

use Kbnet\Starter\ORM\SettingsTable;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;

class Config
{
    /**
     * @var self|null Экземпляр класса
     */
    private static ?self $instance = null;

    /**
     * @var array Кэш настроек
     */
    private array $settingsCache = [];

    /**
     * Конструктор (защищен для Singleton)
     */
    private function __construct()
    {
    }

    /**
     * Запрет клонирования
     */
    private function __clone()
    {
    }

    /**
     * Получить экземпляр класса
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Получить значение настройки
     *
     * @param string $code Код настройки
     * @param mixed $default Значение по умолчанию
     * @param string|null $siteId ID сайта (опционально)
     * @return mixed
     */
    public function get(string $code, mixed $default = null, ?string $siteId = null): mixed
    {
        $cacheKey = $code . '_' . ($siteId ?? 'global');
        
        if (isset($this->settingsCache[$cacheKey])) {
            return $this->settingsCache[$cacheKey];
        }

        // Попытка получить из кэша
        $cache = Cache::createInstance();
        $cacheId = 'kbnet_starter_setting_' . md5($code . '_' . ($siteId ?? ''));
        
        if ($cache->initCache(3600, $cacheId, 'kbnet_starter_settings')) {
            $value = $cache->getVars();
            $this->settingsCache[$cacheKey] = $value;
            return $value;
        }

        // Получение из БД
        $value = SettingsTable::getByCode($code, $siteId);
        
        if ($value === null) {
            $value = $default;
        }

        // Сохранение в кэш
        $cache->startDataCache();
        $cache->endDataCache($value);
        
        $this->settingsCache[$cacheKey] = $value;

        return $value;
    }

    /**
     * Установить значение настройки
     *
     * @param string $code Код настройки
     * @param mixed $value Значение
     * @param string|null $description Описание
     * @param string|null $siteId ID сайта (опционально)
     * @return bool
     */
    public function set(
        string $code,
        mixed $value,
        ?string $description = null,
        ?string $siteId = null
    ): bool {
        $result = SettingsTable::setByCode($code, $value, $description, $siteId);
        
        if ($result) {
            // Очистка кэша
            $this->clearCache($code, $siteId);
            
            // Сброс локального кэша
            $cacheKey = $code . '_' . ($siteId ?? 'global');
            unset($this->settingsCache[$cacheKey]);
        }

        return $result;
    }

    /**
     * Удалить настройку
     *
     * @param string $code Код настройки
     * @param string|null $siteId ID сайта (опционально)
     * @return bool
     */
    public function delete(string $code, ?string $siteId = null): bool
    {
        $result = SettingsTable::deleteByCode($code, $siteId);
        
        if ($result) {
            $this->clearCache($code, $siteId);
            
            $cacheKey = $code . '_' . ($siteId ?? 'global');
            unset($this->settingsCache[$cacheKey]);
        }

        return $result;
    }

    /**
     * Очистить кэш настройки
     *
     * @param string $code Код настройки
     * @param string|null $siteId ID сайта (опционально)
     */
    private function clearCache(string $code, ?string $siteId = null): void
    {
        $cache = Cache::createInstance();
        $cacheId = 'kbnet_starter_setting_' . md5($code . '_' . ($siteId ?? ''));
        $cache->clean($cacheId, 'kbnet_starter_settings');
        
        // Также очищаем тегированный кэш
        $taggedCache = Application::getInstance()->getTaggedCache();
        $taggedCache->clearByTag('kbnet_starter_' . $code);
    }

    /**
     * Получить все настройки для сайта
     *
     * @param string|null $siteId ID сайта
     * @return array
     */
    public function getAll(?string $siteId = null): array
    {
        $filter = [];
        if ($siteId !== null) {
            $filter['LOGIC'] = 'OR';
            $filter[] = ['=SITE_ID' => $siteId];
            $filter[] = ['=SITE_ID' => null];
        }

        $result = SettingsTable::getList([
            'filter' => $filter,
            'select' => ['CODE', 'VALUE', 'DESCRIPTION', 'SITE_ID'],
            'order'  => ['CODE' => 'ASC']
        ]);

        $settings = [];
        while ($row = $result->fetch()) {
            $settings[$row['CODE']] = [
                'VALUE'       => $row['VALUE'],
                'DESCRIPTION' => $row['DESCRIPTION'],
                'SITE_ID'     => $row['SITE_ID']
            ];
        }

        return $settings;
    }

    /**
     * Массовая установка настроек
     *
     * @param array $settings Массив настроек [code => value]
     * @param string|null $siteId ID сайта
     * @return bool
     */
    public function setMultiple(array $settings, ?string $siteId = null): bool
    {
        $success = true;
        
        foreach ($settings as $code => $value) {
            if (!$this->set($code, $value, null, $siteId)) {
                $success = false;
            }
        }

        return $success;
    }
}
