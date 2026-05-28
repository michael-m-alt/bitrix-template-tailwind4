<?php
/**
 * Конфигурация проекта
 * Переопределяет настройки по умолчанию из Local\Starter\Config
 */

return [
    // Режим разработки (true/false)
    'devMode' => false,
    
    // Время жизни кеша по умолчанию (секунды)
    'cacheTtl' => 3600,
    
    // Путь к AJAX обработчику
    'ajaxPath' => '/local/ajax/',
    
    // Путь к компонентам
    'componentsPath' => '/local/components/starter/',
    
    // Дополнительные настройки проекта
    'settings' => [
        // Включить тегированный кеш
        'taggedCache' => true,
        
        // Включить Auto-Composite
        'composite' => false,
        
        // Поддерживаемые языки
        'languages' => ['ru', 'en'],
        
        // Язык по умолчанию
        'defaultLang' => 'ru',
    ],
];
