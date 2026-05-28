<?php
/**
 * Шаблон сайта kbnet-tailwind4
 * Header template
 * 
 * @package kbnet-tailwind4
 * @author Студия K.B.Net <www.kbnet.ru>
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

// Подключение CSS через Asset (Auto-Composite совместимо)
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/styles.css');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/tailwind.css');

// Подключение JS
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/script.js');

// Мета-теги для SEO
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // SEO поля из настроек страницы
    $APPLICATION->ShowMeta('keywords');
    $APPLICATION->ShowMeta('description');
    $APPLICATION->ShowTitle();
    ?>
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <?php
    // Вывод подключенных ассетов в head
    Asset::getInstance()->addString('<meta name="generator" content="Bitrix Starter Kit kbnet.starter">');
    ?>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<!-- Skip to main content for accessibility -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-lg z-50">
    <?= GetMessage('KBNET_SKIP_TO_CONTENT') ?>
</a>

<header class="site-header bg-white shadow-sm sticky top-0 z-40">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center space-x-2 group">
                    <span class="text-xl font-bold text-blue-600 group-hover:text-blue-700 transition-colors">
                        <?= GetMessage('KBNET_SITE_NAME') ?>
                    </span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="hidden md:flex space-x-8" aria-label="<?= GetMessage('KBNET_MAIN_NAVIGATION') ?>">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:menu',
                    '.default',
                    [
                        'ROOT_MENU_TYPE' => 'top',
                        'MENU_CACHE_TYPE' => 'A',
                        'MENU_CACHE_TIME' => '3600',
                        'MENU_CACHE_USE_GROUPS' => 'Y',
                        'MENU_CACHE_GET_VARS' => [],
                        'MAX_LEVEL' => '1',
                        'USE_EXT' => 'N',
                        'DELAY' => 'N',
                        'ALLOW_MULTI_SELECT' => 'N'
                    ],
                    false
                );
                ?>
            </nav>
            
            <!-- Language switcher & Mobile menu button -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="language-switcher hidden sm:block">
                    <?php
                    $languages = \Bitrix\Main\Localization\LanguageTable::getList([
                        'select' => ['LID', 'NAME', 'SORT'],
                        'filter' => ['=ACTIVE' => 'Y'],
                        'order'  => ['SORT' => 'ASC']
                    ])->fetchAll();
                    
                    if (count($languages) > 1):
                    ?>
                    <div class="flex space-x-2" role="group" aria-label="<?= GetMessage('KBNET_LANGUAGE_SWITCHER') ?>">
                        <?php foreach ($languages as $lang): ?>
                            <?php if ($lang['LID'] == LANGUAGE_ID): ?>
                                <span class="px-3 py-1 rounded-md bg-blue-600 text-white text-sm font-medium">
                                    <?= htmlspecialcharsbx($lang['LID']) ?>
                                </span>
                            <?php else: ?>
                                <a href="<?= htmlspecialcharsbx(\Bitrix\Main\Context::getCurrent()->getRequest()->getRequestUri() . '?lang=' . $lang['LID']) ?>"
                                   class="px-3 py-1 rounded-md text-gray-600 hover:bg-gray-100 text-sm font-medium transition-colors"
                                   hreflang="<?= htmlspecialcharsbx($lang['LID']) ?>">
                                    <?= htmlspecialcharsbx($lang['LID']) ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <button type="button" 
                        class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-expanded="false"
                        aria-label="<?= GetMessage('KBNET_TOGGLE_MENU') ?>"
                        id="mobile-menu-button">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:menu',
                    '.default',
                    [
                        'ROOT_MENU_TYPE' => 'top',
                        'MENU_CACHE_TYPE' => 'A',
                        'MENU_CACHE_TIME' => '3600',
                        'MENU_CACHE_USE_GROUPS' => 'Y',
                        'MENU_CACHE_GET_VARS' => [],
                        'MAX_LEVEL' => '1',
                        'USE_EXT' => 'N',
                        'DELAY' => 'N',
                        'ALLOW_MULTI_SELECT' => 'N'
                    ],
                    false,
                    ['COMPONENT_TEMPLATE_PATH' => SITE_TEMPLATE_PATH . '/components/bitrix/menu/mobile']
                );
                ?>
            </div>
        </div>
    </div>
</header>

<main id="main-content" class="flex-grow">
