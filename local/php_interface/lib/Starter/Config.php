<?php
/**
 * Базовый класс конфигурации проекта
 * Управляет настройками, языками и константами
 */

namespace Local\Starter;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

class Config
{
    private static ?Config $instance = null;
    
    /** @var array Конфигурация проекта */
    private array $config = [];
    
    /** @var string Текущий язык */
    private string $currentLang;
    
    /** @var array Поддерживаемые языки */
    private array $supportedLanguages = ['ru', 'en'];
    
    /**
     * Приватный конструктор для паттерна Singleton
     */
    private function __construct()
    {
        $this->currentLang = $this->detectLanguage();
        $this->loadConfig();
        $this->initConstants();
        $this->registerLoc();
    }
    
    /**
     * Получение экземпляра класса (Singleton)
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Определение текущего языка
     * @return string
     */
    private function detectLanguage(): string
    {
        $lang = defined('LANGUAGE_ID') ? LANGUAGE_ID : 'ru';
        
        if (!in_array($lang, $this->supportedLanguages, true)) {
            $lang = 'ru';
        }
        
        return $lang;
    }
    
    /**
     * Загрузка конфигурации
     */
    private function loadConfig(): void
    {
        $configFile = __DIR__ . '/../../config.php';
        
        if (file_exists($configFile)) {
            $this->config = require $configFile;
        } else {
            // Конфигурация по умолчанию
            $this->config = [
                'devMode' => false,
                'cacheTtl' => 3600,
                'ajaxPath' => '/local/ajax/',
                'componentsPath' => '/local/components/starter/',
            ];
        }
    }
    
    /**
     * Инициализация глобальных констант
     */
    private function initConstants(): void
    {
        if (!defined('STARTER_DEV_MODE')) {
            define('STARTER_DEV_MODE', $this->config['devMode'] ?? false);
        }
        
        if (!defined('STARTER_CACHE_TTL')) {
            define('STARTER_CACHE_TTL', $this->config['cacheTtl'] ?? 3600);
        }
        
        if (!defined('STARTER_AJAX_PATH')) {
            define('STARTER_AJAX_PATH', $this->config['ajaxPath'] ?? '/local/ajax/');
        }
    }
    
    /**
     * Регистрация локализации
     */
    private function registerLoc(): void
    {
        Loc::getMessage(__FILE__);
        
        // Добавляем пути для поиска языковых файлов
        Loc::loadMessages(__DIR__);
    }
    
    /**
     * Получение значения из конфигурации
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
    
    /**
     * Проверка режима разработки
     * @return bool
     */
    public function isDevMode(): bool
    {
        return STARTER_DEV_MODE === true;
    }
    
    /**
     * Получение текущего языка
     * @return string
     */
    public function getCurrentLang(): string
    {
        return $this->currentLang;
    }
    
    /**
     * Получение списка поддерживаемых языков
     * @return array
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }
}
